<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    protected $fillable = [
        'employee_id', 'name', 'username', 'email', 'phone',
        'password', 'role_id', 'is_active','online'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    //  Relasi ke Role
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'employee_id', 'nip');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission($permission)
    {
        return in_array($permission, $this->role?->permissions ?? []);
    }


    // HELPER ROLE (biar bisa dipanggil langsung dari user)
    public function isSuperadmin()
    {
        return $this->role?->name === 'Superadmin';
    }

    public function isManagerHRD()
    {
        return $this->role?->name === 'Manager HRD';
    }

    public function isAdminHRD()
    {
        return $this->role?->name === 'Admin HRD';
    }

    public function getRoleNameAttribute()
    {
        return [
            1 => 'super',
            2 => 'manajer',
            3 => 'staf'
        ][$this->role_id];
    }
}
