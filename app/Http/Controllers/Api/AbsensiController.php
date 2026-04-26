<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pegawai;
use App\Models\Absensi;
use App\Models\Periode;

class AbsensiController extends Controller
{
    public function scan(Request $request)
{
    $request->validate([
        'employee_id' => 'required',
        'qr_code' => 'required'
    ]);

    $now = Carbon::now();

    // VALIDASI QR
    $data = json_decode($request->qr_code, true);

    if (!$data || !isset($data['type'])) {
        return response()->json([
            'status' => 'invalid_qr',
            'message' => 'QR tidak valid'
        ], 200);
    }

    if ($data['type'] !== 'absen') {
        return response()->json([
            'status' => 'invalid_qr',
            'message' => 'QR tidak sesuai'
        ], 200);
    }

    if (!isset($data['key']) || $data['key'] !== env('QR_SECRET')) {
        return response()->json([
            'status' => 'invalid_qr',
            'message' => 'QR ilegal'
        ], 200);
    }

    if (!isset($data['periode']) || $data['periode'] !== $now->format('Y-m')) {
        return response()->json([
            'status' => 'invalid_period',
            'message' => 'QR bukan periode ini'
        ], 200);
    }

    if (isset($data['expired']) && $now->toDateString() > $data['expired']) {
        return response()->json([
            'status' => 'expired',
            'message' => 'QR sudah kadaluarsa'
        ], 200);
    }

    // PERIODE
    $periode = Periode::where('bulan', $now->month)
        ->where('tahun', $now->year)
        ->first();

    if (!$periode) {
        return response()->json([
            'status' => 'no_period',
            'message' => 'Periode tidak ditemukan'
        ], 200);
    }

    // PEGAWAI
    $pegawai = Pegawai::where('nip', $request->employee_id)->first();

    if (!$pegawai) {
        return response()->json([
            'status' => 'not_found',
            'message' => 'Pegawai tidak ditemukan'
        ], 200);
    }

    // JAM
    $isMorning = $now->hour >= 6 && $now->hour <= 9;
    $isAfternoon = $now->hour >= 16 && $now->hour <= 19;

    $sudahAbsen = Absensi::where('pegawai_nip', $pegawai->nip)
        ->where('tgl', $now->day)
        ->where('periode_id', $periode->id)
        ->where('bulan', $periode->bulan)
        ->first();

    $absen = null;

    // ABSEN PAGI
    if ($isMorning) {

        if (!$sudahAbsen) {
            $absen = Absensi::create([
                'pegawai_nip' => $pegawai->nip,
                'periode_id' => $periode->id,
                'bulan' => $periode->bulan,
                'tgl' => $now->day,
                'jam_masuk_pagi' => $now->format('H:i:s'),
                'pagi' => 1
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Absen pagi berhasil',
                'data' => $absen
            ]);
        }

        if ($sudahAbsen->pagi == 1) {
            return response()->json([
                'status' => 'already_checked',
                'message' => 'Sudah absen pagi'
            ], 200);
        }

        $sudahAbsen->update([
            'jam_masuk_pagi' => $now->format('H:i:s'),
            'pagi' => 1
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absen pagi berhasil',
            'data' => $sudahAbsen
        ]);
    }

    // ABSEN SORE
    elseif ($isAfternoon) {

        if (!$sudahAbsen) {
            $absen = Absensi::create([
                'pegawai_nip' => $pegawai->nip,
                'periode_id' => $periode->id,
                'bulan' => $periode->bulan,
                'tgl' => $now->day,
                'jam_masuk_sore' => $now->format('H:i:s'),
                'sore' => 1
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Absen sore (tanpa absen pagi)',
                'data' => $absen
            ]);
        }

        if ($sudahAbsen->sore == 1) {
            return response()->json([
                'status' => 'already_checked',
                'message' => 'Sudah absen sore'
            ], 200);
        }

        $sudahAbsen->update([
            'jam_masuk_sore' => $now->format('H:i:s'),
            'sore' => 1
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absen sore berhasil',
            'data' => $sudahAbsen
        ]);
    }

    // BUKAN JAM ABSEN
    return response()->json([
        'status' => 'not_time',
        'message' => 'Bukan jam absen'
    ], 200);
}
    public function today(Request $request)
    {
        $now = Carbon::now();
        $tahun = Periode::where('tahun',$now->year)->first();
        $data = Absensi::where('pegawai_nip', $request->employee_id)
            ->where('tgl', $now->day)
            ->where('bulan', $now->month)
            ->where('periode_id', $tahun->id)
            ->first();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}

// public function scan(Request $request)
// {
//     $request->validate([
//         'employee_id' => 'required',
//         'qr_code' => 'required'
//     ]);
//     $now = Carbon::now();
//     // VALIDASI QR
//     $data = json_decode($request->qr_code, true);
//     if (!$data || !isset($data['type'])) {
//         return response()->json([
//             'message' => 'QR tidak valid'
//         ], 400);
//     }
//     if ($data['type'] !== 'absen') {
//         return response()->json([
//             'message' => 'QR tidak sesuai'
//         ], 400);
//     }
//     // cek SECRET
//     if (!isset($data['key']) || $data['key'] !== env('QR_SECRET')) {
//         return response()->json([
//             'message' => 'QR ilegal'
//         ], 403);
//     }
//     // cek periode (bulanan)
//     if (!isset($data['periode']) || $data['periode'] !== $now->format('Y-m')) {
//         return response()->json([
//             'message' => 'QR bukan periode ini'
//         ], 400);
//     }
//     // cek expired
//     if (isset($data['expired']) && $now->toDateString() > $data['expired']) {
//         return response()->json([
//             'message' => 'QR sudah kadaluarsa'
//         ], 400);
//     }
//     // CEK PERIODE DB
//     $periode = Periode::where('bulan', $now->month)
//         ->where('tahun', $now->year)
//         ->first();
//     if (!$periode) {
//         return response()->json([
//             'message' => 'Periode tidak ditemukan'
//         ], 404);
//     }
//     // CEK PEGAWAI
//     $pegawai = Pegawai::where('nip', $request->employee_id)->first();
//     if (!$pegawai) {
//         return response()->json([
//             'message' => 'Pegawai tidak ditemukan'
//         ], 404);
//     }
//     // JAM ABSEN
//     $isMorning = $now->hour >= 6 && $now->hour <= 9;
//     $isAfternoon = $now->hour >= 16 && $now->hour <= 19;
//     $sudahAbsen = Absensi::where('pegawai_nip', $pegawai->nip)
//         ->where('tgl', $now->day)
//         ->where('periode_id', $periode->id)
//         ->where('bulan', $periode->bulan)
//         ->first();
//     // ABSEN PAGI
//     if ($isMorning) {
//         if (!$sudahAbsen) {
//             $absen = Absensi::create([
//                 'pegawai_nip' => $pegawai->nip,
//                 'periode_id' => $periode->id,
//                 'bulan' => $periode->bulan,
//                 'tgl' => $now->day,
//                 'jam_masuk_pagi' => $now->format('H:i:s'),
//                 'pagi' => 1
//             ]);
//         } else {
//             if ($sudahAbsen->pagi == 1) {
//                 return response()->json([
//                     'message' => 'Sudah absen pagi'
//                 ], 409);
//             }
//             $sudahAbsen->update([
//                 'jam_masuk_pagi' => $now->format('H:i:s'),
//                 'pagi' => 1
//             ]);
//             $absen = $sudahAbsen;
//         }
//     }
//     // ABSEN SORE
//     elseif ($isAfternoon) {
//         if (!$sudahAbsen) {
//             // otomatis buat record walau belum pagi
//                 $absen = Absensi::create([
//                     'pegawai_nip' => $pegawai->nip,
//                     'periode_id' => $periode->id,
//                       'bulan' => $periode->bulan,
//                     'tgl' => $now->day,
//                     'jam_masuk_sore' => $now->format('H:i:s'),
//                     'sore' => 1
//                 ]);
//                 return response()->json([
//                     'message' => 'Absen sore (tanpa absen pagi)',
//                     'data' => $absen
//                 ]);
//         }
//         if ($sudahAbsen->sore == 1) {
//             return response()->json([
//                 'message' => 'Sudah absen sore'
//             ], 409);
//         }
//         $sudahAbsen->update([
//             'jam_masuk_sore' => $now->format('H:i:s'),
//             'sore' => 1
//         ]);
//         $absen = $sudahAbsen;
//     }
//     // BUKAN JAM ABSEN
//     else {
//         return response()->json([
//             'message' => 'Bukan jam absen'
//         ], 422);
//     }
//     return response()->json([
//         'message' => 'Absen berhasil',
//         'data' => $absen
//     ]);
//     }



