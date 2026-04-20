<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periode';

    protected $fillable = [
        'tahun',
        'bulan',
        'hari',
        'status',
    ];
    protected $casts = [
    'hari' => 'array',
    ];
    protected $appends = ['nama_bulan'];

    /**
     * 1 Periode punya banyak Absensi
     */
    // public function absensi()
    // {
    //     return $this->hasMany(Absensi::class, 'periode_id');
    // }
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'periode_id', 'id');
    }

    /**
     * 1 Periode punya banyak Tunjangan Transport
     */
    public function tunjanganTransport()
    {
        return $this->hasMany(TunjanganTransportPegawai::class, 'periode_id');
    }

    /**
     * Accessor biar tampil rapi: 2026/12
     */
    public function getPeriodeFormatAttribute()
    {
        return $this->tahun . '/' . str_pad($this->bulan, 2, '0', STR_PAD_LEFT);
    }
     public static function bulanList()
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    }
    public function getNamaBulanAttribute()
    {
        return self::bulanList()[$this->bulan] ?? '-';
    }
}
