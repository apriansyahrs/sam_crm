<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->insert([
            ['id' => 1, 'name' => 'ASM', 'created_at' => null, 'updated_at' => null],
            ['id' => 2, 'name' => 'ASC', 'created_at' => null, 'updated_at' => null],
            ['id' => 3, 'name' => 'DSF/DM', 'created_at' => null, 'updated_at' => null],
            ['id' => 4, 'name' => 'AR', 'created_at' => null, 'updated_at' => null],
            ['id' => 5, 'name' => 'COO', 'created_at' => '2022-10-10 23:04:34', 'updated_at' => '2022-10-22 18:40:52'],
            ['id' => 6, 'name' => 'CSO', 'created_at' => '2022-10-12 16:58:18', 'updated_at' => '2022-10-12 16:58:18'],
            ['id' => 7, 'name' => 'RKAM', 'created_at' => '2022-10-22 16:54:24', 'updated_at' => '2022-10-22 16:54:24'],
            ['id' => 8, 'name' => 'KAM', 'created_at' => '2022-10-24 05:22:24', 'updated_at' => '2022-10-24 05:22:24'],
            ['id' => 9, 'name' => 'CSOFASTEV', 'created_at' => '2022-12-05 17:13:14', 'updated_at' => '2022-12-05 17:13:14'],
        ]);
    }
}
