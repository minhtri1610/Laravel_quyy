<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\createAdminUsersSeeder;
use Database\Seeders\createRolesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            createAdminUsersSeeder::class,
            createRolesSeeder::class
        ]);
    }
}
