<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('divisions')->insert([
            ['id' => 1, 'business_entity_id' => 1, 'name' => 'MSIS', 'created_at' => null, 'updated_at' => null],
            ['id' => 2, 'business_entity_id' => 2, 'name' => 'ORAIMO', 'created_at' => null, 'updated_at' => null],
            ['id' => 3, 'business_entity_id' => 2, 'name' => 'TECNO', 'created_at' => null, 'updated_at' => null],
            ['id' => 4, 'business_entity_id' => 2, 'name' => 'REALME', 'created_at' => null, 'updated_at' => null],
            ['id' => 5, 'business_entity_id' => 3, 'name' => '-', 'created_at' => null, 'updated_at' => null],
            ['id' => 6, 'business_entity_id' => 2, 'name' => 'SPAREPARTDIST', 'created_at' => '2022-09-30 09:10:05', 'updated_at' => '2022-09-30 09:10:05'],
            ['id' => 7, 'business_entity_id' => 4, 'name' => 'FASTEV', 'created_at' => '2022-12-04 05:47:59', 'updated_at' => '2022-12-04 05:47:59'],
            ['id' => 8, 'business_entity_id' => 5, 'name' => 'ZTE', 'created_at' => '2024-06-11 11:45:32', 'updated_at' => '2024-06-11 11:45:32'],
        ]);
    }
}
