<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\User;
use App\Models\Pegawai;

class ScannerController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/hadirku');
        }
        return view('front.dashboard', compact('user'));
    }
}
