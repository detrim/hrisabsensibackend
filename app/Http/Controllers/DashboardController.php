<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use app\Models\Role;
use Spatie\Activitylog\Models\Activity;
use app\Models\User;
use App\Models\Pegawai;
use App\Models\Absensi;
use Carbon\Carbon;
use App\Models\TunjanganTransportPegawai;


class DashboardController extends Controller
{
    public function index()
    {
        $total_pegawai = Pegawai::count();
        $today = carbon::today();
        if(auth()->user()->isManagerHRD()){
            $pegawai_kontrak = Pegawai::where('status_pegawai', 'kontrak')->count();
            $pegawai_tetap = Pegawai::where('status_pegawai', 'tetap')->count();
            $pegawai_magang = Pegawai::where('jabatan', 'magang')->count();
            $laki_laki = Pegawai::where('jenis_kelamin', 'L')->count();
            $perempuan = Pegawai::where('jenis_kelamin', 'P')->count();
            // 5 pegawai terbaru
           $pegawai_baru = Pegawai::where('status', 1)
            ->where('status_pegawai', 'kontrak')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

            return view('dashboard', compact(
                'total_pegawai',
                'pegawai_kontrak',
                'pegawai_tetap',
                'pegawai_magang',
                'laki_laki',
                'perempuan',
                'pegawai_baru'
            ));
        }elseif(auth()->user()->isAdminHRD()){
            $totalHariIni = Absensi::whereDate('created_at', $today)->count();
            $izin = Absensi::whereDate('created_at', $today)
                ->where('keterangan', 'izin')
                ->count();
            $sakit = Absensi::whereDate('created_at', $today)
                ->where('keterangan', 'sakit')
                ->count();
            $cuti = Absensi::whereDate('created_at', $today)
                ->where('keterangan', 'cuti')
                ->count();
            $pegawai_nonaktif = Pegawai::where('status', 0)->count();
            $pegawai_aktif = Pegawai::where('status', 1)->count();
            $tunjanganBulanIni = TunjanganTransportPegawai::whereMonth('created_at', $today->month)
                            ->whereYear('created_at', $today->year)
                            ->sum('total_tunjangan');
            $tunjanganTahunIni = TunjanganTransportPegawai::whereYear('created_at', $today->year)
                            ->sum('total_tunjangan');
            return view('dashboard',
            compact('pegawai_nonaktif','total_pegawai',
            'totalHariIni','pegawai_aktif','tunjanganTahunIni',
            'tunjanganBulanIni','izin','sakit','cuti'));
        }else{
                $totalUser = User::count();
                $userOnline = User::where('online', 1)->count();
                $logHariIni = Activity::whereDate('created_at', Carbon::today())->count();
                return view('dashboard', compact(
                    'totalUser',
                    'userOnline',
                    'logHariIni'
                ));
        }

    }
}
