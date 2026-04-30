<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\User;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PegawaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class DataPegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with(['user.role'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        return view('pegawai.index', compact('pegawai'));
    }
    public function cari(Request $request)
    {
    $keyword = $request->search;

    $pegawai = Pegawai::when($keyword, function ($query) use ($keyword) {
            $query->where('nip', 'like', "%{$keyword}%")
                  ->orWhere('nama', 'like', "%{$keyword}%")
                  ->orWhere('jabatan', 'like', "%{$keyword}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);
    return view('pegawai.index', compact('pegawai'));
    }
    public function create()
    {
        return view('pegawai.create');
    }
    private function savePegawai(Request $request, $pegawai = null)
    {
        $pegawaiId = $pegawai?->id;
        $request->validate([
            'foto' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
           'nip' => ['required','numeric','digits_between:8,20', Rule::unique('pegawai','nip')->ignore($pegawaiId)],
            'email' => ['required','email', Rule::unique('pegawai','email')->ignore($pegawaiId)],
            'nama' => 'required|string|max:255',
            'no_hp' => ['required','regex:/^\+62[0-9]+$/'],
            'tempat_lahir' => 'required',
            'kecamatan' => 'required',
            'kabupaten' => 'required',
            'provinsi' => 'required',
            'alamat_lengkap' => 'required',
            'tanggal_lahir' => 'required|date',
            'status_kawin' => 'required',
            'jumlah_anak' => 'required|integer|min:0|max:99',
            'tanggal_masuk' => 'required|date',
            'jabatan' => 'required',
            'departemen' => 'required',
            'pendidikan' => 'required',
            'jenis_kelamin' => 'required',
            'status_pegawai' => 'required',
            'status' => 'required'
        ]);

            if(empty($request->file('foto')) && !empty($request->oldFoto)){
                $path = $request->oldFoto;
            }else{
                if (!empty($pegawaiId) && !empty($request->oldFoto)) {
                    Storage::disk('public')->delete($request->oldFoto);
                    $image = $request->file('foto');
                }else{
                     $image = $request->file('foto') ?? 'img/avatar-default.jpg';
                }
                $manager = new ImageManager(new Driver());
                 // nama file unik
                 $filename = uniqid() . '.jpg';
                 // baca image
                 $img = $manager->read($image);
                 // resize + compress
                 $img->resize(300, 300) // bisa ubah sesuai kebutuhan
                     ->toJpeg(70); // kualitas 70% (optimal)
                 Storage::put('photos/' . $filename, $img->encode());
                 // path untuk disimpan ke DB
                 $path = 'photos/' . $filename;
            }
        $status = $request->status;
        $tanggalKeluar = $status == 1 ? null : Carbon::now()->format('Y-m-d');
        $pendidikan = array_values($request->pendidikan);
        $data = [
            'foto' => $path,
            'nip' => $request->nip,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'tempat_lahir' => $request->tempat_lahir,
            'alamat_kecamatan' => $request->kecamatan,
            'alamat_kabupaten' => $request->kabupaten,
            'alamat_provinsi' => $request->provinsi,
            'alamat_lengkap' => $request->alamat_lengkap,
            'tanggal_lahir' => $request->tanggal_lahir,
            'usia' => $request->usia,
            'status_kawin' => $request->status_kawin,
            'jumlah_anak' => $request->jumlah_anak,
            'tanggal_masuk' => $request->tanggal_masuk,
            'jabatan' => $request->jabatan,
            'departemen' => $request->departemen,
            'jenis_kelamin' => $request->jenis_kelamin,
            'status_pegawai' => $request->status_pegawai,
            'pendidikan' => $pendidikan,
            'status' =>$status,
            'tanggal_keluar' => $tanggalKeluar
        ];
        // CREATE / UPDATE
        if ($pegawai) {
            $user = User::where('employee_id', $request->nip)->first();
        if ($user) {
            $datauser = [
                'email' => $request->email,
            ];
            $user->update($data);
        }
            $pegawai->update($data);
            activity()
                ->useLog('Pegawai')
                ->causedBy(auth()->user())
                ->performedOn($pegawai)
                ->log('Update data pegawai');
            return $pegawai;
        }
        $pegawai = Pegawai::create($data);
        activity()
            ->useLog('Pegawai')
            ->causedBy(auth()->user())
            ->performedOn($pegawai)
            ->log('Tambah data pegawai');
        return $pegawai;
    }

    public function store(Request $request)
    {
        $this->savePegawai($request);
        return redirect()->route('pegawai.create')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
            $pegawai = Pegawai::findOrFail($id);
            $pendidikan = $pegawai->pendidikan;
        return view('pegawai.edit', compact('pegawai', 'pendidikan'));
    }
    public function detail($id)
    {
            $pegawai = Pegawai::findOrFail($id);
            $pendidikan = $pegawai->pendidikan;
        return view('pegawai.detail', compact('pegawai', 'pendidikan'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $this->savePegawai($request, $pegawai);
        return redirect()->route('pegawai.edit', $id)
            ->with('success', 'Data berhasil diupdate');
    }

    public function filter(Request $request)
    {
        $tahun=(int) $request->masa_kerja;
        $tanda = $request->operator;
        $jabatan = $request->jabatan;
        $pegawai = Pegawai::selectRaw(" *,
            TIMESTAMPDIFF(YEAR, tanggal_masuk, CURDATE()) as masa_kerja
        ")
        ->whereIn('jabatan', (array) $jabatan)
        ->having('masa_kerja', $tanda , $tahun)
        ->orderBy('created_at', 'desc')
        ->paginate(10);;
        return view('pegawai.index', compact('pegawai'));
    }
    public function downloadPdf($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pendidikan = $pegawai->pendidikan;
        $pdf = Pdf::loadView('pegawai.pdf', compact('pegawai', 'pendidikan'))
            ->setPaper('A4', 'portrait');
        return $pdf->download('pegawai-' . $pegawai->nip . '.pdf');
    }

    public function exportPdf()
    {
        $pegawai = Pegawai::all();
        $pdf = Pdf::loadView('pegawai.export_pdf', compact('pegawai'))
            ->setPaper('A4', 'landscape');
        return $pdf->download('data-pegawai-export-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }


    public function exportExcel()
    {
        return Excel::download(new PegawaiExport, 'data-pegawai-export-'. Carbon::now()->format('Y-m-d') .'.xlsx');
    }
    public function bulkStatus(Request $request)
    {
        $status = $request->status;
        // update semua pegawai sekaligus
        $tanggalKeluar = $status == 1 ? null : Carbon::now()->format('Y-m-d');
        Pegawai::whereIn('id', $request->ids)->update([
            'status' => $status,
            'tanggal_keluar' => $tanggalKeluar
        ]);
        // ambil data pegawai untuk update user
        $pegawais = Pegawai::whereIn('id', $request->ids)->get();
        foreach ($pegawais as $pegawai) {
            User::where('employee_id', $pegawai->nip)
                ->update(['is_active' => $status]);
        }

        activity()
            ->useLog('Pegawai')
            ->causedBy(auth()->user())
            ->withProperties([
                'status' => $status,
                'total_pegawai' => count($request->ids ?? []),
                'data' => $pegawais
            ])
            ->log('Update status pegawai massal');
        return response()->json([
            'success' => true,
            'status' => $status,
            'total_pegawai' => count($request->ids ?? []),
            'message' => 'Berhasil update status'
        ], 200);
    }

    public function delete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data dipilih'
            ]);
        }
        $pegawais = Pegawai::whereIn('id', $ids)->get();
        foreach ($pegawais as $pegawai) {
            // Hapus user jika ada
            User::where('employee_id', $pegawai->nip)->delete();
            // Hapus foto jika ada
            if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                Storage::disk('public')->delete($pegawai->foto);
            }
        }

        $pegawaiData = Pegawai::whereIn('id', $ids)->get();
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'ids' => $ids,
                'total' => count($ids),
                'data' => $pegawaiData
            ])
            ->log('Hapus data pegawai massal');
        Pegawai::whereIn('id', $ids)->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data pegawai dan user berhasil dihapus'
        ]);

    }

    public function generateNip($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $key = env('QR_SECRET');

        // payload QR (data utama)
        $payload = [
            'type' => 'pegawai',
            'nip'  => $pegawai->nip,
            'nama' => $pegawai->nama,
        ];

        // encode payload
        $text = json_encode($payload);

        // signature (pengaman)
        $signature = hash_hmac('sha256', $text, $key);

        // gabungkan data + signature
        $qrData = [
            'data' => $payload,
            'signature' => $signature
        ];

        $qrString = json_encode($qrData);

        // generate QR
        $qrCode = QrCode::create($qrString)->setSize(300);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // convert ke base64 untuk PDF
        $qr = base64_encode($result->getString());

        // load PDF
        $pdf = Pdf::loadView('pegawai.qrnip', [
            'pegawai' => $pegawai,
            'qr'      => $qr
        ]);

        return $pdf->download('id-card-'.$pegawai->nip.'.pdf');
    }
}
