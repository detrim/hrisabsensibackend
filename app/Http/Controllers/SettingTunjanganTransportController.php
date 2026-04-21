<?php

namespace App\Http\Controllers;

use App\Models\SettingTunjanganTransport;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\LokasiKantor;
use Illuminate\Support\Facades\Log;


class SettingTunjanganTransportController extends Controller
{
    public function index()
    {
        $kantor = LokasiKantor::first();
        $lokasi = $kantor->lokasi ?? 'Data Kosong';
       $tarif = SettingTunjanganTransport::first();
       if ($tarif && $tarif->tarif_per_km !== null) {
            $tunjangan = 'Rp. ' . number_format($tarif->tarif_per_km, 0, ',', '.') . '/Km';
        } else {
            $tunjangan = 'Data Kosong';
        }

        return view('setting.index', compact('lokasi','tunjangan'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'tarif_per_km' => 'required|numeric|min:0'
            ]);
            $tarif = $request->tarif_per_km;
            $jarak = $request->jarak_km ?  : 25;
            // ambil data lama sebelum dihapus (untuk log)
            $old = SettingTunjanganTransport::all();
            SettingTunjanganTransport::truncate();
            $setting = SettingTunjanganTransport::create([
                'tarif_per_km' => $tarif,
                'max_jarak' => $jarak,
            ]);
            activity()
                ->useLog('Setting')
                ->causedBy(auth()->user())
                ->performedOn($setting)
                ->withProperties([
                    'old_data' => $old,
                    'new_data' => $setting,
                ])
                ->log('Update tarif transport');
            return response()->json([
                'status' => true,
                'message' => 'Berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            activity()
                ->useLog('Setting')
                ->causedBy(auth()->user())
                ->log('Gagal update tarif transport: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan'
            ], 500);
        }
    }

    // public function store(Request $request)
    // {
    //         try {
    //             $request->validate([
    //                 'tarif_per_km' => 'required|numeric|min:0'
    //             ]);
    //             $tarif = $request->tarif_per_km;
    //             SettingTunjanganTransport::truncate();
    //             SettingTunjanganTransport::create([
    //                     'tarif_per_km' => $tarif,
    //                 ]);
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Berhasil disimpan',
    //             ]);
    //         } catch (\Exception $e) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Gagal menyimpan'
    //             ], 500);
    //         }
    // }
}
