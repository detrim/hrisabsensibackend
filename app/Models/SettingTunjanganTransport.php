<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SettingTunjanganTransport extends Model
{
    use HasFactory;
    protected $table = 'setting_tunjangan_transport';
    protected $fillable = ['tarif_per_km','max_jarak'];
}
