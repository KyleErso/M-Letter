<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Buat instance controller baru.
     *
     * @return void
     */
    public function __construct()
    {
        // Pastikan hanya tamu yang bisa mengakses, kecuali logout
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Tentukan redirect setelah login berdasarkan role.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $role = auth()->user()->role;

        switch ($role) {
            case 'mahasiswa':
                return '/mahasiswa/dashboard';
            case 'admin':
                return '/admin/dashboard';
            case 'kaprodi':
                return '/kaprodi/dashboard';
            case 'superadmin':
                return '/superadmin/dashboard';
            default:
                return '/login';
        }
    }

    /**
     * Logout user dan reset session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Anda telah berhasil logout.');
    }
}
