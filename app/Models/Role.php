<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'permissions','role'];

    protected $casts = [
        'permissions' => 'array'
    ];

    // Relasi ke User
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
    // Accessor untuk permissions
    public function getPermissionsAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    public function isSuperadmin()
    {
        return $this->name === 'Superadmin';
    }

    public function isManagerHRD()
    {
        return $this->name === 'Manager HRD';
    }

    public function isAdminHRD()
    {
        return $this->name === 'Admin HRD';
    }
}
