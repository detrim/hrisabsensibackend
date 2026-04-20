<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TunjanganTransportPegawai extends Model
{
    use HasFactory;
    protected $table = 'tunjangan_transport_pegawai';
    protected $fillable = [
        'pegawai_nip',
        'jarak_km',
        'jumlah_hari_masuk',
        'total_tunjangan',
        'periode_id'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_nip', 'nip');
    }

    // LOGIC PERHITUNGAN
    public static function hitungTunjangan($jarak, $hari, $tarif, $pegawaiTetap)
    {
        // hanya pegawai tetap
        if (!$pegawaiTetap) {
            return 0;
        }
        // minimal hari kerja
        if ($hari < 19) {
            return 0;
        }
        // pembulatan km
        $jarakBulat = floor($jarak);
        if (($jarak - $jarakBulat) >= 0.5) {
            $jarakBulat = ceil($jarak);
        }
        // minimal jarak
        if ($jarakBulat <= 5) {
            return 0;
        }
        // maksimal jarak
        if ($jarakBulat > 25) {
            $jarakBulat = 25;
        }
        return $tarif * $jarakBulat * $hari;
    }
}
