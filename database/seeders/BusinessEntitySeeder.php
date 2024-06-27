<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('business_entities')->insert([
            ['id' => 1, 'name' => 'PT.MSI', 'created_at' => null, 'updated_at' => null],
            ['id' => 2, 'name' => 'CV.TOP', 'created_at' => null, 'updated_at' => null],
            ['id' => 3, 'name' => '-', 'created_at' => null, 'updated_at' => null],
            ['id' => 4, 'name' => 'PT.MKLI', 'created_at' => '2022-12-04 05:47:22', 'updated_at' => '2022-12-04 05:47:22'],
            ['id' => 5, 'name' => 'CV.MAJU', 'created_at' => '2024-06-11 11:44:29', 'updated_at' => '2024-06-11 11:44:29'],
        ]);
    }
}
