<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Lokasi;
use App\Models\LokasiKantor;

class LokasiController extends Controller
{
    public function index(){
        $pegawai = Pegawai::with('lokasi')->where('status', 1)->paginate(100);
        $data = LokasiKantor::first();
        return view('absensi.lokasi', compact('pegawai','data'));
    }
   public function kantor(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        foreach ($data as $item) {
            $lok = $item['lokasi'];
            $lat = $item['latitude'];
            $lng = $item['longitude'];
            }
        LokasiKantor::truncate();
            LokasiKantor::create([
                'lokasi' => $lok,
                'latitude' => $lat,
                'longitude' => $lng,
            ]);
        return response()->json([
            'success' => true,
            'message' => 'Lokasi kantor berhasil disimpan ',
        ]);
    }

    public function pegawai(Request $request)
    {
            $data = json_decode($request->getContent(), true);
            foreach ($data as $item) {
                $lok = $item['nama_lokasi'];
                $id = $item['id_pegawai'];
                $lat = $item['latitude'];
                $lng = $item['longitude'];
            }
        $pegawai = Pegawai::find($id);
        $nip = $pegawai->nip;
        $lokasi = Lokasi::where('pegawai_nip', $nip)->first();
        if ($lokasi) {
            $lokasi->update([
                'lokasi' => $lok,
                'latitude'    => $lat,
                'longitude'   => $lng,
            ]);
        } else {
            Lokasi::create([
                'pegawai_nip' => $nip,
                'lokasi' => $lok,
                'latitude'    => $lat,
                'longitude'   => $lng,
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Lokasi berhasil disimpan'
        ]);
    }


    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $pegawai = Pegawai::with('lokasi')
            ->where('status', 1)
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%");
            })
            ->get();
        // NORMALISASI lokasi jadi string
        $pegawai->transform(function ($p) {
            $p->lokasi_text = is_object($p->lokasi)
                ? ($p->lokasi->lokasi ?? null)
                : $p->lokasi;

            return $p;
        });
        return response()->json($pegawai->values());
    }

}
