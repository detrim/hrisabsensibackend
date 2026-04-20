<?php

namespace App\Http\Controllers;

use App\Models\TunjanganTransportPegawai;
use App\Models\SettingTunjanganTransport;
use App\Models\Pegawai;
use App\Models\Periode;
use App\Models\Absensi;
use App\Models\Lokasi;
use App\Models\LokasiKantor;
use App\Services\TunjanganTransportService;
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
        $data = Periode::query()
        ->where('status', 1)
        ->when($keyword, function ($q) use ($keyword) {
            $q->where('tahun', 'like', "%{$keyword}%");
        })
        ->orderBy('tahun', 'desc')
        ->orderBy('bulan', 'desc')
        ->get();
        return response()->json($data);
    }
    public function tunjangan($thn,$bln,$id)
    {
        $data = Periode::findOrFail($id);
        $tunjangan = TunjanganTransportPegawai::with('pegawai')
        ->where('periode_id', $id)
        ->paginate(100);
        return view('tunjangan.tunjangan', compact('tunjangan','data', 'bln'));
    }
   public function tunjangansearch(Request $request)
    {
        $keyword = $request->keyword;
        $id = $request->id;
        $pegawai = TunjanganTransportPegawai::with('pegawai')
        ->where('periode_id', $id)
        ->when($keyword, function ($query) use ($keyword) {
            $query->whereHas('pegawai', function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%");
            });
        })
        ->get();

        return response()->json($pegawai);
    }

}
