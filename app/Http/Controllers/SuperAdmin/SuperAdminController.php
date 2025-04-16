<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    /**
     * Tampilkan dashboard super admin dengan daftar user beserta relasi fakultas dan prodi.
     */
    public function index(Request $request)
    {
        $query = User::query();

        $query->with([
            'admin.fakultas',
            'admin.prodi',
            'kaprodi.fakultas',
            'kaprodi.prodi',
            'mahasiswa.prodi.fakultas'
        ]);

        // Filter berdasarkan nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan fakultas melalui relasi admin atau kaprodi
        if ($request->filled('fakultas')) {
            $fakultas = $request->fakultas;
            $query->where(function ($q) use ($fakultas) {
                $q->whereHas('admin.fakultas', function ($subQuery) use ($fakultas) {
                    $subQuery->where('kode_fakultas', $fakultas);
                })
                ->orWhereHas('kaprodi.fakultas', function ($subQuery) use ($fakultas) {
                    $subQuery->where('kode_fakultas', $fakultas);
                });
            });
        }

        // Filter berdasarkan prodi melalui relasi admin atau kaprodi
        if ($request->filled('prodi')) {
            $prodi = $request->prodi;
            $query->where(function ($q) use ($prodi) {
                $q->whereHas('admin.prodi', function ($subQuery) use ($prodi) {
                    $subQuery->where('kode_prodi', $prodi);
                })
                ->orWhereHas('kaprodi.prodi', function ($subQuery) use ($prodi) {
                    $subQuery->where('kode_prodi', $prodi);
                });
            });
        }

        $users    = $query->latest()->get();
        $fakultas = Fakultas::all();
        $prodi    = Prodi::all();

        return view('superadmin.dashboard', compact('users', 'fakultas', 'prodi'));
    }

    /**
     * Tampilkan form edit user.
     */
    public function edit($id)
    {
        $user     = User::findOrFail($id);
        $fakultas = Fakultas::all();
        $prodi    = Prodi::all();

        return view('superadmin.edit', compact('user', 'fakultas', 'prodi'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, $id)
    {
        // Validasi input, termasuk validasi password yang bersifat nullable
        $rules = [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $id,
            'role'          => 'required|in:mahasiswa,admin,kaprodi,superadmin',
            'kode_fakultas' => 'nullable|exists:fakultas,kode_fakultas',
            'kode_prodi'    => 'nullable|exists:prodis,kode_prodi',
            'password'      => 'nullable|confirmed|min:6',
        ];

        $validated = $request->validate($rules);

        $user = User::findOrFail($id);

        // Persiapkan data yang akan diupdate
        $dataToUpdate = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        // Jika field password diisi, update password dengan hash
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataToUpdate);

        // Update data relasi untuk role admin atau kaprodi bila diperlukan
        if ($user->role === 'admin' && $user->admin) {
            $user->admin()->update([
                'kode_fakultas' => $request->kode_fakultas,
                'kode_prodi'    => $request->kode_prodi,
            ]);
        } elseif ($user->role === 'kaprodi' && $user->kaprodi) {
            $user->kaprodi()->update([
                'kode_fakultas' => $request->kode_fakultas,
                'kode_prodi'    => $request->kode_prodi,
            ]);
        }

        return redirect()->route('superadmin.dashboard')
                         ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Hapus user (soft delete).
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('superadmin.dashboard')
                         ->with('success', 'User berhasil dihapus.');
    }
}
