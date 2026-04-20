<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periode;
use App\Models\Pegawai;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    public function index(){

    }
    public function absensi(Request $request, $thn,$bln,$tgl){
        $id = $request->id;
        $data = Periode::findOrFail($request->id);
        Carbon::setLocale('id');
        $tanggal = Carbon::create($data->tahun, $data->bulan, $tgl);
        $hari = $tanggal->translatedFormat('l');
        $pegawai = Pegawai::with(['absensi' => function ($q) use ($id, $bln, $tgl) {
            $q->select('*')
                ->where('periode_id', $id)
                ->where('bulan', $bln)
                ->where('tgl', $tgl);
            }])
            ->where('status', 1)
            ->paginate(100);
        return view('absensi.absensi', compact('pegawai','data','tgl','hari'));
    }
    public function search(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        $tgl = $data['tgl'];
        $bln = $data['bulan'];
        $keyword = $data['keyword'];

        $pegawai = Pegawai::with(['absensi' => function ($q) use ($id, $bln, $tgl) {
        $q->select('id', 'pegawai_nip', 'pagi', 'sore', 'keterangan', 'periode_id', 'tgl', 'bulan')
            ->where('periode_id', $id)
            ->where('bulan', $bln)
            ->where('tgl', $tgl)
            ->with('periode');
        }])
        ->where('status', 1)
        ->when($keyword, function ($query) use ($keyword) {
            $query->where('nama', 'like', "%$keyword%");
        })
        ->get();
        return response()->json($pegawai);
    }
    public function update(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $absen = Absensi::where('periode_id', $data['id'])
        ->where('tgl', $data['tgl'])
        ->where('pegawai_nip', $data['nip'])
        ->first();
                if ($data['jenis'] === 'pagi') {
                    if ($absen) {
                        $absen->pagi = $data['value'];
                        $absen->sore =  $absen->sore;
                        $absen->save();
                    }
                    else {
                        Absensi::create([
                            'pegawai_nip' => $data['nip'],
                            'periode_id' => $data['id'],
                            'bulan' => $data['bulan'],
                            'tgl' => $data['tgl'],
                            'pagi' => $data['value'],
                        ]);
                    }
            }elseif ($data['jenis'] === 'sore'){
                if ($absen){
                $absen->pagi = $absen->pagi;
                $absen->sore = $data['value'];
                $absen->save();
               }
               else {
                    Absensi::create([
                        'pegawai_nip' => $data['nip'],
                        'periode_id' => $data['id'],
                        'bulan' => $data['bulan'],
                        'tgl' => $data['tgl'],
                        'sore' => $data['value'],
                    ]);
                }
            }else{
                if ($absen){
                $absen->pagi = $absen->pagi;
                $absen->sore = $absen->sore;
                $absen->keterangan = $data['keterangan'];
                $absen->save();
               }
               else {
                    Absensi::create([
                        'pegawai_nip' => $data['nip'],
                        'periode_id' => $data['id'],
                        'bulan' => $data['bulan'],
                        'tgl' => $data['tgl'],
                        'keterangan' => $data['keterangan'],
                    ]);
                }
            }
        return response()->json([
            'success' => true,
            'message' => 'sukses',
        ]);
    }

}
