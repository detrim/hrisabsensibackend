<?php

namespace App\Http\Controllers;

use App\Models\TunjanganTransportPegawai;
use App\Models\SettingTunjanganTransport;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class TunjanganTransportPegawaiController extends Controller
{
    public function index()
    {
        return TunjanganTransportPegawai::with('pegawai')->get();
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
