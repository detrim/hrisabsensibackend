<?php

namespace App\Http\Controllers;

use App\Models\TunjanganTransportPegawai;
use App\Models\SettingTunjanganTransport;
use App\Models\Pegawai;
use App\Models\Periode;
use App\Models\Absensi;
use Illuminate\Http\Request;

class TunjanganTransportPegawaiController extends Controller
{
    public function index()
    {
        $data = Periode::where('status',1)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(12);
        return view('tunjangan.index', compact('data'));
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
    public function tunjangan($thn,$bln,$id)
    {
        $data = Periode::findOrFail($id);
        $pegawai = Absensi::with(['pegawai', 'lokasi'])
            ->select(
                'pegawai_nip',
                'periode_id',
                'bulan'
            )
            ->whereHas('pegawai', function ($q) {
                    $q->where('status_pegawai', 'tetap');
                })
            ->selectRaw('SUM(pagi) as jumlah_hari_masuk')
            ->groupBy('pegawai_nip', 'periode_id', 'bulan')
            ->get();
            dd($pegawai,$thn);
            return view('tunjangan.tunjangan', compact('pegawai','data', 'bln'));
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
    function hitungJarakKm($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371; // km

    $latFrom = deg2rad($lat1);
    $lonFrom = deg2rad($lon1);
    $latTo   = deg2rad($lat2);
    $lonTo   = deg2rad($lon2);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $a = sin($latDelta / 2) * sin($latDelta / 2) +
         cos($latFrom) * cos($latTo) *
         sin($lonDelta / 2) * sin($lonDelta / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c; // hasil km
}
}
