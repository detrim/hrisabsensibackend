<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiKantor extends Model
{
    protected $table = 'lokasi_kantor';

    protected $fillable = [
        'lokasi',
        'latitude',
        'longitude',
    ];
}
