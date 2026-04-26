<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AuthController extends Controller
{

public function captcha()
{
    $captcha = rand(1000, 9999);
    $token = \Str::random(40);

    \Cache::put('captcha_'.$token, $captcha, now()->addMinutes(5));

    return response()->json([
        'captcha' => $captcha,
        'token' => $token
    ]);
}
public function postlogin(Request $request)
{
    $validator = Validator::make($request->all(), [
        'username' => 'required',
        'password' => 'required',
        'role' => 'required',
        'captcha' => 'required',
        'captcha_token' => 'required'
    ]);
    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first()
        ], 422);
    }
    // ================= CAPTCHA (CACHE) =================
    $captchaCache = \Cache::get('captcha_'.$request->captcha_token);
    if (!$captchaCache || $request->captcha != $captchaCache) {
        return response()->json([
            'status' => 'error',
            'message' => 'Captcha salah'
        ], 422);
    }
    // hapus setelah dipakai
    \Cache::forget('captcha_'.$request->captcha_token);
    // ================= LOGIN =================
    $login = trim($request->username);
    $field = filter_var($login, FILTER_VALIDATE_EMAIL)
        ? 'email'
        : (preg_match('/^[0-9+]+$/', $login) ? 'phone' : 'username');
    $user = User::where($field, $login)->first();
    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'User tidak ditemukan'
        ], 404);
    }
    if (!\Hash::check($request->password, $user->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Password salah'
        ], 401);
    }
    $role = Role::where('name', $request->role)->first();
    if ($role->id !== $user->role_id) {
        return response()->json([
            'status' => 'error',
            'message' => 'Role tidak sesuai'
        ], 403);
    }
    if (!$user->is_active) {
        return response()->json([
            'status' => 'error',
            'message' => 'Akun tidak aktif'
        ], 403);
    }
     // REMEMBER ME
    $remember = $request->boolean('remember');
    Auth::guard('web')->login($user, $remember);
    $token = $user->createToken('web-token')->plainTextToken;
    // simpan token ke session (biar Blade bisa pakai kalau perlu)
    session(['api_token' => $token]);
    session(['remember_token' => $remember]);
    $request->session()->regenerate();
    // redirect
            $redirect = null;
            $roleName = null;
        if ($role->isSuperadmin()) {
            $redirect = 'superadmin';
            $roleName = 'Superadmin';
        } elseif ($role->isManagerHRD()) {
            $redirect = 'managerhrd';
            $roleName = 'Manager HRD';
        } elseif ($role->isAdminHRD()) {
            $redirect = 'adminhrd';
            $roleName = 'Admin HRD';
        }
        $user->update([
            'online' => 1
        ]);
        activity()
            ->useLog('Auth')
            ->causedBy($user)
            ->log("Login sebagai {$roleName}");

    return response()->json([
        'status' => 'success',
        'message' => 'Login berhasil',
        'redirect' => $redirect
    ]);
}

        public function logout(Request $request)
        {
            $user = Auth::user();
            if ($user) {
                $user->update([
                    'online' => 0
                ]);
                activity()
                ->useLog('Auth')
                ->causedBy($user)
                ->log('Logout dari sistem');
                $user->setRememberToken(null);
                $user->save();
                }
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                Auth::logout();

            return redirect('/login')->with('success', 'Berhasil logout');
        }

}
