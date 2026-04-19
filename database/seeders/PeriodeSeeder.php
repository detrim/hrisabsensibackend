<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Periode;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'tahun' => 2020,
                'bulan' => 4,
            ],
            [
                'tahun' => 2021,
                'bulan' => 2,
            ],
        ];

        foreach ($data as $item) {
            // Hitung jumlah hari dalam bulan
            $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $item['bulan'], $item['tahun']);

            // Buat array tanggal [1,2,3,...]
            $hari = range(1, $jumlahHari);

            Periode::create([
                'tahun' => $item['tahun'],
                'bulan' => $item['bulan'],
                'hari'  => $hari,
            ]);
        }
    }
}
