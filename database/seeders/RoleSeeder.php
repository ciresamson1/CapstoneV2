<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'Admin',
            'description' => 'Full system access'
        ]);

        Role::create([
            'name' => 'Project Manager',
            'description' => 'Manages projects and tasks'
        ]);

        Role::create([
            'name' => 'Team Member',
            'description' => 'Works on assigned tasks'
        ]);

        Role::create([
            'name' => 'Client',
            'description' => 'Read-only project access'
        ]);

        Role::factory(10)->create();
    }
}