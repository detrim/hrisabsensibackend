<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'lokasi';

    protected $fillable = [
        'pegawai_nip',
        'lokasi',
        'latitude',
        'longitude',
    ];

     /**
     * Lokasi milik 1 Pegawai
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_nip', 'nip');
    }
    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'pegawai_nip', 'pegawai_nip');
    }
}
