<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pegawai;

class PegawaiOnlineController extends Controller
{
    public function index()
    {
        return view('online.index');
    }
    public function data()
    {
        $onlinePegawai = User::with('pegawai')->where('online', 1)->get();
        return response()->json($onlinePegawai);
    }
}
