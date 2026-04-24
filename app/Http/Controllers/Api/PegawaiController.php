<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;


class PegawaiController extends Controller{
    // public function search(Request $r)
    // {
    //     return Pegawai::where('nama', 'like', '%' . $r->q . '%')
    //         ->limit(10)
    //         ->get(['id', 'nama']);
    // }
    public function search(Request $request)
    {
        $keyword = trim($request->q);
        if (!$keyword) {
            return response()->json([]);
        }
        $usedNip = User::pluck('employee_id')->toArray();
        $data = Pegawai::where('nama', 'like', "%{$keyword}%")
            ->limit(10)
            ->get(['id', 'nama', 'nip']);
        return $data->map(function ($item) use ($usedNip) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'nip' => $item->nip,
                'disabled' => in_array($item->nip, $usedNip),
            ];
        });

        // $data = Pegawai::query()
        //     ->where('nama', 'like', "%{$keyword}%")
        //     ->limit(10)
        //     ->get(['id', 'nama', 'nip']);
        // $result = $data->map(function ($item) {
        // $isUsed = User::where('employee_id', $item->nip)->exists();
        //     return [
        //         'id' => $item->id,
        //         'nama' => $item->nama,
        //         'nip' => $item->nip,
        //         'disabled' => $isUsed,
        //     ];
        // });

        return response()->json($result);
    }
    public function checkusername(Request $r)
    {
        $exists = User::where('username', $r->username)->exists();
        return response()->json([
            'exists' => $exists
        ]);
    }


        public function searchLocal(Request $request)
        {
            $wilayah  = json_decode(file_get_contents(storage_path('app/public/wilayah.json')), true);
            // CARI DI KECAMATAN
           $q = strtolower($request->q ?? '');
            $result = [];
            foreach ($wilayah['kecamatan'] as $kec) {
                $namaKec = strtolower($kec['name'] ?? '');
                if ($q && str_contains($namaKec, $q)) {
                    $result[] = [
                        'type' => 'kecamatan',
                        'kode_kecamatan' => $kec['code'],
                        'nama_kecamatan' => $kec['name'],
                        'nama_kabupaten' => $kec['regency_name'],
                        'nama_provinsi'  => $kec['province_name'],
                    ];
                }
            }
            return response()->json($result);
        }
        public function searchKabupaten(Request $request)
        {
            $q = strtolower($request->q ?? '');
            if (strlen($q) < 3) {
                return response()->json([]);
            }
            $wilayah = json_decode(file_get_contents(storage_path('app/public/wilayah.json')), true);
            $result = [];
            foreach ($wilayah['kabupaten'] as $kab) {
                $namaKab = strtolower($kab['name'] ?? '');
                if (str_contains($namaKab, $q)) {
                    $result[] = [
                        'kode_kabupaten' => $kab['code'],
                        'nama_kabupaten' => $kab['name'],
                        'nama_provinsi'  => $kab['province_name'],
                        'kode_provinsi'  => $kab['province_code'],
                    ];
                }
            }
            return response()->json($result);
        }
}

