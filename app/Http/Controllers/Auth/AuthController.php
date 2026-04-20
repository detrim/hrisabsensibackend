<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        session(['captcha' => rand(1000, 9999)]);
        return view('auth.login');
    }

        public function postlogin(Request $request)
        {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
                'role' => 'required',
                'captcha' => 'required'
            ]);
            // 1. CEK CAPTCHA
            if ($request->captcha != session('captcha')) {
                return back()->withErrors([
                    'captcha' => 'Captcha salah'
                ])->withInput();
            }
            $login = trim($request->username);
            $field = filter_var($login, FILTER_VALIDATE_EMAIL)
                ? 'email'
                : (preg_match('/^[0-9+]+$/', $login) ? 'phone' : 'username');
            // 2. CEK USER ADA ATAU TIDAK
            $user = User::where($field, $login)->first();
            if (!$user) {
                return back()->withErrors([
                    'username' => 'User tidak ditemukan'
                ])->withInput();
            }
            // 3. CEK PASSWORD
            if (!\Hash::check($request->password, $user->password)) {
                return back()->withErrors([
                    'password' => 'Password salah'
                ])->withInput();
            }
            // 4. CEK ROLE
            $role = Role::where('name', $request->role)->first();
            // cek apakah role yang dipilih sesuai dengan role user (by ID)
            // CEK ACTIVE ATAU TIDAK
            if ($role->id !== $user->role_id) {
                return back()->withErrors([
                    'role' => 'Role tidak sesuai'
                ])->withInput();
            }elseif(!$user->is_active){
                return back()->withErrors([
                    'akun' => 'Akun tidak aktif'
                ])->withInput();
            }
            // REMEMBER ME
            $remember = $request->has('remember');
            // LOGIN (pakai remember)
            Auth::login($user, $remember);
            $token = $user->createToken('web-token')->plainTextToken;
            $request->session()->regenerate();
            // simpan token ke session (biar Blade bisa pakai kalau perlu)
            session(['api_token' => $token]);
            // redirect sesuai role
            if ($role->isSuperadmin()) {
                return redirect('superadmin');
            } elseif ($role->isManagerHRD()) {
                return redirect('managerhrd');
            } elseif ($role->isAdminHRD()) {
                return redirect('adminhrd');
            }
                return redirect('/dashboard');
        }

        public function logout(Request $request)
        {
            // dd(Auth::user());
            Auth::logout();
            // hapus session
            // $request->user()->currentAccessToken()->delete();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->with('success', 'Berhasil logout');
        }

}
