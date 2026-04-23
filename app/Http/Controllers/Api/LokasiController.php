<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Lokasi;
use App\Models\LokasiKantor;
use App\Models\SettingTunjanganTransport;

class LokasiController extends Controller
{
    public function getLokasi()
    {
        $data = LokasiKantor::first();
        return response()->json($data);
    }
    public function getTunjangan()
    {
       $data = SettingTunjanganTransport::first();
        if ($data) {
            $tarif = $data->tarif_per_km !== null
                ? 'Rp. ' . number_format($data->tarif_per_km, 0, ',', '.') . '/Km'
                : 'Data Kosong';
           $max = $data->max_jarak ? $data->max_jarak . ' Km' : '0 Km';
        } else {
            $tarif = 'Data Kosong';
            $max = 0;
        }
        return response()->json([
            'tarif_per_km' => $tarif,
            'max_jarak' => $max,
        ]);
    }
    public function getPegawai()
    {
    $pegawai = Pegawai::with('lokasi')
        ->where('status', 1)
        ->get()
        ->map(function ($p) {
            $lokasiRaw = $p->lokasi?->lokasi;
            $desa = null;
            $kab = null;
            $prov = null;
            $lokasi = null;

            if ($lokasiRaw) {
                $data = explode(',', $lokasiRaw);

                $desa = $data[0] ?? null;
                $kab  = $data[1] ?? null;
                $prov = $data[2] ?? null;

                $lokasi = implode(', ', array_filter([$desa, $kab, $prov]));
            }

            return [
                'id' => $p->id,
                'nama' => $p->nama,
                // data lokasi hasil format
                'lokasi' => $lokasi,
                // optional detail kalau mau dipakai JS
                'desa' => $desa,
                'kabupaten' => $kab,
                'provinsi' => $prov,
                // dari relasi
                'pegawai_nip' => $p->lokasi?->pegawai_nip,
                'latitude' => $p->lokasi?->latitude,
                'longitude' => $p->lokasi?->longitude,
            ];
        });
    return response()->json($pegawai);
    }
}
