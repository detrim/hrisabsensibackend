<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pegawai;

class GenerateTunjanganTransport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-tunjangan-transport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    $setting = SettingTunjanganTransport::latest()->first();
    $pegawaiList = Pegawai::where('status_pegawai', 'tetap')->get();
    foreach ($pegawaiList as $pegawai) {
        $jarak = $pegawai->jarak_km; // asumsi ada
        $hari = $pegawai->jumlah_hari_masuk; // asumsi ada
        $total = TunjanganTransportPegawai::hitungTunjangan(
            $jarak,
            $hari,
            $setting->tarif_per_km,
            true
        );
        TunjanganTransportPegawai::updateOrCreate(
            [
                'pegawai_nip' => $pegawai->nip,
                'periode' => now()->format('Y-m-01')
            ],
            [
                'jarak_km' => $jarak,
                'jumlah_hari_masuk' => $hari,
                'total_tunjangan' => $total
            ]
        );
    }
    }
}
