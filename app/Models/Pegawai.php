<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'foto', 'nip', 'nama', 'email', 'no_hp', 'tempat_lahir',
        'alamat_kecamatan', 'alamat_kabupaten', 'alamat_provinsi', 'alamat_lengkap',
        'tanggal_lahir', 'status_kawin', 'jumlah_anak', 'tanggal_masuk',
        'jabatan', 'departemen', 'usia', 'pendidikan', 'status','jenis_kelamin','status_pegawai', 'tanggal_keluar'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'pendidikan' => 'array',
        'status' => 'boolean'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id', 'nip');
    }
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'pegawai_nip', 'nip');
    }
    public function tunjanganTransport()
    {
        return $this->hasMany(TunjanganTransportPegawai::class, 'pegawai_nip', 'nip');
    }
    public function isTetap()
    {
        return $this->status_pegawai === 'tetap';
    }
    public function lokasi()
    {
        return $this->hasOne(Lokasi::class, 'pegawai_nip', 'nip');
    }


    public static function totalByTanggal($tanggal)
    {
        return self::where('tanggal_masuk', '<=', $tanggal)
            ->where(function ($q) use ($tanggal) {
                $q->whereNull('tanggal_keluar')
                ->orWhere('tanggal_keluar', '>=', $tanggal);
            })
            ->count();
    }

}
