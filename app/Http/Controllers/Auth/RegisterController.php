<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Mahasiswa;
use App\Models\Kaprodi;
use App\Models\Admin;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Setelah registrasi, arahkan ke halaman dashboard superadmin.
     *
     * @var string
     */
    protected $redirectTo = '/superadmin/dashboard';

    /**
     * Tampilkan halaman form registrasi.
     * Hanya Super Admin yang boleh mengakses halaman ini.
     */
    public function showRegistrationForm()
    {
        if (!auth()->check() || auth()->user()->role !== 'superadmin') {
            abort(403, 'Hanya Super Admin yang dapat mendaftarkan user.');
        }
    
        $fakultas = \App\Models\Fakultas::all();
        $prodis = \App\Models\Prodi::all();
    
        return view('auth.register', compact('fakultas', 'prodis'));
    }
    
    /**
     * Proses data registrasi dari form.
     * Hanya Super Admin yang boleh melakukan proses ini.
     */
    public function register(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'superadmin') {
            abort(403, 'Hanya Super Admin yang dapat mendaftarkan user.');
        }

        $this->validator($request->all())->validate();

        $this->create($request->all());

        return redirect($this->redirectTo)->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Validasi input dari form register.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'id'       => ['required', 'integer', 'unique:users'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:mahasiswa,admin,kaprodi,superadmin'],
            // Pastikan field kode_prodi dan (jika perlu) kode_fakultas ada untuk peran admin/kaprodi
            'kode_prodi' => ['required_if:role,mahasiswa,admin,kaprodi'],
            'kode_fakultas' => ['required_if:role,admin,kaprodi'],
        ]);
    }

    /**
     * Simpan data user baru ke database.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        

        $user = User::create([
            'id'       => $data['id'],
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        // Ambil tahun ajaran aktif menggunakan kolom 'status'
        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();

        // Simpan ke tabel terkait berdasarkan peran
        switch ($data['role']) {
            case 'mahasiswa':
                Mahasiswa::create([
                    'id'                => $user->id,
                    'kode_prodi'        => $data['kode_prodi'],
                    'tahun_ajaran_kode' => $tahunAjaranAktif->kode_tahun,
                    'alamat'            => 'isi alamat',
                ]);
                break;
            case 'admin':
                Admin::create([
                    'id'            => $user->id,
                    'kode_prodi'    => $data['kode_prodi'],
                    'kode_fakultas' => $data['kode_fakultas'],
                ]);
                break;
            case 'kaprodi':
                Kaprodi::create([
                    'id'            => $user->id,
                    'kode_prodi'    => $data['kode_prodi'],
                    'kode_fakultas' => $data['kode_fakultas'],
                ]);
                break;
                
        }

        return $user;
    }
}
