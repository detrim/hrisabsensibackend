<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periode;
use App\Models\Pegawai;
use Carbon\Carbon;

class PeriodeController extends Controller
{

    public function index()
    {
        $data = Periode::orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(12);
        $bulan = Periode::bulanList();
        return view('absensi.index', compact('data', 'bulan'));
    }
     public function search(Request $request)
    {
        $keyword = $request->keyword;
        $data = Periode::where('tahun', 'like', "%$keyword%")
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();
        return response()->json($data);
    }
    public function updateStatus(Request $request, $id)
    {
        $data = Periode::findOrFail($id);
        $data->status = $request->status;
        $data->save();

        return back();
    }
    public function update(Request $request, $id)
    {
        $this->savestore($request, $id);
        return back()->with('error', 'Data periode sudah ada !');
    }
    public function store(Request $request)
    {
        $this->savestore($request);
        return back()->with('error', 'Data periode sudah ada !');
    }
    public function savestore(Request $request, $id = null)
    {
        $request->validate([
            'tahun' => 'required|digits:4',
            'bulan' => 'required|min:1|max:12',
        ]);
        $data = [
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
        ];
        // cek supaya tidak double (unique constraint juga sudah ada)
        $exists = Periode::where('tahun', $request->tahun)
            ->where('bulan', $request->bulan)
            ->first();

        if (empty($exists)) {
            if (!empty($id)) {
                // dd(empty($exists),$id);
                $periode = Periode::findOrFail($id);
                $periode->update($data);
                return redirect()->route('periode.index')->with('success', 'Berhasil diupdate');
            }
            Periode::create($data);
            return redirect()->route('periode.index')->with('success', 'Berhasil ditambahkan');
        }
    return back();
    }
    public function bulan($id)
    {
        $data = Periode::findOrFail($id);
        Carbon::setLocale('id');
        $today = Carbon::now();
        $hariIni = $today->day;
        $dataHari = [];
        foreach ($data->hari ?? [] as $h) {
            $tanggal = Carbon::create($data->tahun, $data->bulan, $h);
            $formathari = $tanggal->translatedFormat('l');
            $formattanggal = $tanggal->translatedFormat('d');
            $tanggalDb = $tanggal->format('Y-m-d');
            $total = Pegawai::totalByTanggal($tanggalDb);
            $dataHari[] = [
                'dd' => $h,
                'hari' => $formathari,
                'tanggal' => $formattanggal,
                'total' => $total
            ];
        }
        // dd($tanggalDb,$total,$tanggal);
        return view('absensi.bulan', compact('data', 'dataHari', 'hariIni'));
    }

    public function hari(Request $request)
    {
        $absensi = Periode::findOrFail($request->periode_id);
        // ambil data lama
        $hariLama = $absensi->hari ?? [];
        // pastikan integer semua
        $hariLama = array_map('intval', $hariLama);
        // JIKA EDIT (ada tanggal_lama)
        if (!empty($request->tanggal_lama)) {
            // hapus tanggal lama
            $hariLama = array_diff($hariLama, [(int) $request->tanggal_lama]);
            // tambah tanggal baru (kalau belum ada)
            if (!in_array((int)$request->hari, $hariLama)) {
                $hariLama[] = (int) $request->hari;
            }
        } else {
            // JIKA TAMBAH
            if (!in_array((int)$request->hari, $hariLama)) {
                $hariLama[] = (int) $request->hari;
            }
        }
        // urutkan
        sort($hariLama);
        // reset index array
        $absensi->hari = array_values($hariLama);
        $absensi->save();
        return back()->with('success', 'Berhasil');
    }
    public function hapushari(Request $request)
    {
        $absensi = Periode::findOrFail($request->periode_id);
        $hariLama = $absensi->hari ?? [];
        // pastikan integer
        $hariLama = array_map('intval', $hariLama);
        // hapus hari yang dipilih
        $hariLama = array_diff($hariLama, [(int)$request->tanggal]);
        // rapikan index + urutkan
        sort($hariLama);
        $absensi->hari = array_values($hariLama);
        $absensi->save();
        return back()->with('success', 'Tanggal berhasil dihapus');
    }
}
