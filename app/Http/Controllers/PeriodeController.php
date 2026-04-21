<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periode;
use App\Models\Pegawai;
use Carbon\Carbon;
use App\Services\TunjanganTransportService;
use App\Models\TunjanganTransportPegawai;


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
       $data = Periode::query()
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('tahun', 'like', "%{$keyword}%");
            })
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

    $service = new TunjanganTransportService();
    $hasil = $service->hitung($id);
        foreach ($hasil as $item) {
            TunjanganTransportPegawai::create([
                'pegawai_nip' => $item['pegawai_nip'],
                'periode_id' => $item['periode_id'],
                'jarak_km' => $item['jarak_dibulatkan'],
                'jumlah_hari_masuk' => $item['jumlah_hari_masuk'],
                'total_tunjangan' => $item['tunjangan_transport'],
            ]);
        }
       return back()->with('success', 'Data Absensi Close, Cek Tunjangan');
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
        $hariLama = $absensi->hari ?? [];
        $hariLama = array_map('intval', $hariLama);
        $hariBaru = (int) $request->hari;
            // tambah baru → cek duplikat
            if (in_array($hariBaru, $hariLama)) {
                return back()->with('error', 'Hari sudah ada di absensi');
            }
            $hariLama[] = $hariBaru;
        sort($hariLama);
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
