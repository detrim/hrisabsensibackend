<?php

namespace App\Http\Controllers;

use App\Models\TunjanganTransportPegawai;
use App\Models\SettingTunjanganTransport;
use App\Models\Pegawai;
use App\Models\Periode;
use Illuminate\Http\Request;

class TunjanganTransportPegawaiController extends Controller
{
    public function index()
    {
        $data = Periode::where('status',1)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(12);
        $bulan = Periode::bulanList();
        return view('tunjangan.index', compact('data', 'bulan'));
    }
    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $data = Periode::where('tahun', 'like', "%$keyword%")
            ->where('status',1)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();
        return response()->json($data);
    }
    public function tunjangan()
    {

        $data = TunjanganTransportPegawai::with(['pegawai', function($q) {
            $q->where('status_pegawai', 'tetap')
            ->where('status', 1)
            ->get();
        }
        ])
        ->paginate(10);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_nip' => 'required|exists:pegawai,nip',
            'jarak_km' => 'required|numeric',
            'jumlah_hari_masuk' => 'required|integer',
            'periode' => 'required|date'
        ]);

        $pegawai = Pegawai::findOrFail($request->pegawai_nip);
        $setting = SettingTunjanganTransport::latest()->first();

        $total = TunjanganTransportPegawai::hitungTunjangan(
            $request->jarak_km,
            $request->jumlah_hari_masuk,
            $setting->tarif_per_km,
            $pegawai->isTetap()
        );

        return TunjanganTransportPegawai::create([
            'pegawai_nip' => $request->pegawai_nip,
            'jarak_km' => $request->jarak_km,
            'jumlah_hari_masuk' => $request->jumlah_hari_masuk,
            'total_tunjangan' => $total,
            'periode' => $request->periode
        ]);
    }
}
