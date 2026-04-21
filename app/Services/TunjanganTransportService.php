<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\LokasiKantor;
use App\Models\SettingTunjanganTransport;

class TunjanganTransportService
{
    public function hitung($id)
    {
        $lokasiKantor = LokasiKantor::first();
        if (!$lokasiKantor) {
            return [];
        }
        // ambil base fare dari setting
        $setting = SettingTunjanganTransport::first();
        $baseFare = $setting->tarif_per_km ?? 0;
        $maxJarak = $setting->max_jarak ?? 25;
        $pegawai = Absensi::with(['pegawai', 'lokasi'])
            ->where('periode_id',$id)
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
        $result = [];
        foreach ($pegawai as $item) {
            if (!$item->lokasi) {
                continue;
            }
            // hitung jarak km
            $jarak = $this->haversineKm(
                $item->lokasi->latitude,
                $item->lokasi->longitude,
                $lokasiKantor->latitude,
                $lokasiKantor->longitude
            );
            // pembulatan km
            $km = $this->roundKm($jarak);
            //  minimal 19 hari atau jarak < 5 km tidak dihitung
            if ($item->jumlah_hari_masuk < 19 || $km < 5) {
                $tunjangan = 0;
            } else {
                $kmFinal = min($km, $maxJarak);
                $tunjangan = $baseFare * $kmFinal * $item->jumlah_hari_masuk;
            }
            $result[] = [
                'pegawai_nip' => $item->pegawai_nip,
                'periode_id' => $item->periode_id,
                'bulan' => $item->bulan,
                'jumlah_hari_masuk' => $item->jumlah_hari_masuk,
                'jarak_asli' => round($jarak, 2),
                'jarak_dibulatkan' => $km,
                'tunjangan_transport' => $tunjangan
            ];
        }
        return $result;
    }

    private function roundKm($km)
    {
        $integer = floor($km);
        $decimal = $km - $integer;

        if ($decimal < 0.5) {
            return $integer;
        }

        return ceil($km);
    }

    private function haversineKm($lat1, $lon1, $lat2, $lon2)
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

        return $earthRadius * $c;
    }
}
