<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        foreach (['user', 'audit', 'finance', 'admin'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
