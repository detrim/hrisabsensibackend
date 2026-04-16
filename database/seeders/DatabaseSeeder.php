<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $superadmin = Role::create([
            'name' => 'Superadmin',
            'permissions' => [
                'role' => ['Y'],
                'user' => ['C','R','U','D'],
                'dashboard' => ['R'],
                'employee' => ['X'],
                'transport_allowance' => ['X'],
                'transport_setting' => ['X'],
                'log' => ['R']
            ]
        ]);

        $managerHrd = Role::create([
            'name' => 'Manager HRD',
            'permissions' => [
                'role' => ['X'],
                'user' => ['RO','UO'],
                'dashboard' => ['R'],
                'employee' => ['R'],
                'transport_allowance' => ['RO'],
                'transport_setting' => ['X'],
                'log' => ['X']
            ]
        ]);

        $adminHrd = Role::create([
            'name' => 'Admin HRD',
            'permissions' => [
                'role' => ['X'],
                'user' => ['RO','UO'],
                'dashboard' => ['R'],
                'employee' => ['C','R','U','D'],
                'transport_allowance' => ['RO'],
                'transport_setting' => ['C','R','U','D'],
                'log' => ['X']
            ]
        ]);

        // Employee untuk superadmin
        $pegawai = Pegawai::create([
            'nip' => '12345678',
            'nama' => 'Super Admin',
            'email' => 'admin@super.com',
            'no_hp' => '+6281234567890',
            'tempat_lahir' => 'Jakarta',
            'alamat_kecamatan' => 'Cempaka Putih',
            'alamat_kabupaten' => 'Jakarta Pusat',
            'alamat_provinsi' => 'DKI Jakarta',
            'alamat_lengkap' => 'Jl. Superadmin No.1',
            'tanggal_lahir' => '1990-01-01',
            'status_kawin' => 'kawin',
            'jumlah_anak' => 2,
            'tanggal_masuk' => '2020-01-01',
            'jabatan' => 'Manager',
            'departemen' => 'Executive',
            'jenis_kelamin' => 'L',
            'status_pegawai' => 'kontrak',
            'usia' => 34,
            'pendidikan' => [
                ['jenjang' => 'S1', 'nama' => 'UMB', 'tahun' => 2012],
                ['jenjang' => 'S2', 'nama' => 'UGM', 'tahun' => 2015]
            ]
        ]);

        // Superadmin user
        User::create([
            'employee_id' => $pegawai->nip,
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => $pegawai->email,
            'password' => Hash::make('Password123'),
            'role_id' => $superadmin->id,
            'is_active' => true
        ]);

                // Pegawai Admin HRD
        $pegawaiHrd = Pegawai::create([
            'nip' => '87654321',
            'nama' => 'Admin HRD',
            'email' => 'admin.hrd@company.com',
            'no_hp' => '+6289876543210',
            'tempat_lahir' => 'Bandung',
            'alamat_kecamatan' => 'Coblong',
            'alamat_kabupaten' => 'Bandung',
            'alamat_provinsi' => 'Jawa Barat',
            'alamat_lengkap' => 'Jl. HRD No.2',
            'tanggal_lahir' => '1992-02-02',
            'status_kawin' => 'kawin',
            'jumlah_anak' => 0,
            'tanggal_masuk' => '2021-01-01',
            'jabatan' => 'Staf',
            'departemen' => 'HRD',
            'jenis_kelamin' => 'L',
            'status_pegawai' => 'kontrak',
            'usia' => 32,
            'pendidikan' => [
                ['jenjang' => 'S1', 'nama' => 'UI', 'tahun' => 2014]
            ]
        ]);

        // User Admin HRD
        User::create([
            'employee_id' => $pegawaiHrd->nip,
            'name' => 'Admin HRD',
            'username' => 'adminhrd',
            'email' => $pegawaiHrd->email,
            'password' => Hash::make('Password123'),
            'role_id' => $adminHrd->id, // pastikan role admin_hrd sudah ada
            'is_active' => true
        ]);


    }
}
