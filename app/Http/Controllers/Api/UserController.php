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
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

public function index(Request $request)
{
    $validator = Validator::make($request->all(), [
        'employee_id' => 'required',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'validation_error',
            'message' => $validator->errors()->first()
        ], 200);
    }

    $user = User::where('employee_id', $request->employee_id)->first();

    if (!$user) {
        return response()->json([
            'status' => 'not_found',
            'message' => 'User tidak ditemukan !',
        ], 200);
    }

    if (!Hash::check($request->password, $user->password)) {
        return response()->json([
            'status' => 'wrong_password',
            'message' => 'Password salah !',
        ], 200);
    }

    Auth::login($user);

    $token = $user->createToken('api-Token')->plainTextToken;

    return response()->json([
        'status' => 'success',
        'message' => 'Login berhasil',
        'Authorization' => [
            'token' => $token,
            'type' => 'Bearer',
        ],
        'data' => [
            'id' => $user->id,
            'name' => $user->name,
            'employee_id' => $user->employee_id,
            'phone' => $user->phone,
            'email' => $user->email,
        ]
    ], 200);
}

    // public function index(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'employee_id' => 'required',
    //         'password' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => $validator->errors()->first()
    //         ], 422);
    //     }
    //     // 2. CARI USER
    //     $user = User::where('employee_id', $request->employee_id)->first();
    //     if (!$user) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'User tidak ditemukan !',
    //         ], 402);
    //     }
    //     // 3. CEK PASSWORD (AMAN & JELAS)
    //     if (!Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Password salah !',
    //         ], 403);
    //     }
    //     // 4. LOGIN MANUAL (tanpa Auth::attempt biar stabil API)
    //     Auth::login($user);
    //     // 5. TOKEN SANCTUM
    //     $token = $user->createToken('api-Token')->plainTextToken;
    //     // 6. ACTIVITY LOG (opsional)
    //     activity()
    //         ->useLog('Auth')
    //         ->causedBy($user)
    //         ->log("Login sebagai staf");
    //     // 7. RESPONSE
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Login berhasil',
    //         'Authorization' => [
    //             'token' => $token,
    //             'type' => 'Bearer',
    //         ],
    //         'valToken' => 'Bearer ' . $token,
    //         'data' => [
    //             'id' => $user->id,
    //             'name' => $user->name,
    //             'username' => $user->username,
    //             'employee_id' => $user->employee_id,
    //             'phone' => $user->phone,
    //             'email' => $user->email,
    //         ]
    //     ], 200);
    // }

}
