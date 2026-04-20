<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'pegawai_nip',
        'tgl',
        'bulan',
        'periode_id',
        'pagi',
        'sore',
        'keterangan'
    ];

     /**
     * Absensi milik 1 Pegawai
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_nip', 'nip');
    }
    public function lokasi()
    {
        return $this->hasOne(Lokasi::class, 'pegawai_nip', 'pegawai_nip');
    }
}
