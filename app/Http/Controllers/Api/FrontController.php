<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\User;

class FrontController extends Controller
{
    public function postlogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }
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
        if (!$user->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun tidak aktif'
            ], 403);
        }
       if ((int) $user->role_id !== 4) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bukan akun staf'
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
                $redirect = 'scanner';
                $roleName =  $user->nama;
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

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        'status' => 'success',
        'message' => 'Berhasil logout'
    ]);
}
}
