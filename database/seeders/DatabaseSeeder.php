<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BusinessEntitySeeder::class,
            DivisionSeeder::class,
            RegionSeeder::class,
            ClusterSeeder::class,
            PositionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        Artisan::call('shield:super-admin', ['--user' => 1]);
        Artisan::call('shield:generate --all');
    }
}
