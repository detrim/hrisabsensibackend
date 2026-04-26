<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Staf',
            'role' =>  4,
            'permissions' => [
                'role' => ['X'],
                'user' => ['x'],
                'dashboard' => ['RO'],
                'employee' => ['RO'],
                'transport_allowance' => ['RO'],
                'transport_setting' => ['X'],
                'log' => ['X']
            ]
        ]);
    }
}
