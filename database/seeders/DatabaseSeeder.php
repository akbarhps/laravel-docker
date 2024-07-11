<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            "USER" => "User",
            "ADMIN" => "Admin",
            "DEV" => "Developer",
        ];
        foreach ($roles as $id => $role) {
            Role::factory()->create([
                'id' => strtoupper($id),
                'name' => $role,
            ]);
        }
    }
}
