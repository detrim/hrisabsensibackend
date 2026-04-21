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
        'max_jarak',
        'periode_id'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_nip', 'nip');
    }
    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id', 'id');
    }

}
