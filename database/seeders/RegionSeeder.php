<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('regions')->insert([
            ['id' => 1, 'business_entity_id' => 1, 'division_id' => 1, 'name' => 'SWJ', 'created_at' => null, 'updated_at' => null],
            ['id' => 3, 'business_entity_id' => 1, 'division_id' => 1, 'name' => 'NWJ', 'created_at' => null, 'updated_at' => null],
            ['id' => 5, 'business_entity_id' => 2, 'division_id' => 3, 'name' => 'NWJ', 'created_at' => null, 'updated_at' => null],
            ['id' => 6, 'business_entity_id' => 1, 'division_id' => 1, 'name' => 'NCJ', 'created_at' => null, 'updated_at' => null],
            ['id' => 8, 'business_entity_id' => 2, 'division_id' => 3, 'name' => 'NCJ', 'created_at' => null, 'updated_at' => null],
            ['id' => 9, 'business_entity_id' => 1, 'division_id' => 1, 'name' => 'SCJ', 'created_at' => null, 'updated_at' => null],
            ['id' => 11, 'business_entity_id' => 2, 'division_id' => 3, 'name' => 'SCJ', 'created_at' => null, 'updated_at' => null],
            ['id' => 13, 'business_entity_id' => 2, 'division_id' => 4, 'name' => 'BIGCIREBON', 'created_at' => null, 'updated_at' => null],
            ['id' => 14, 'business_entity_id' => 2, 'division_id' => 4, 'name' => 'BIGTEGAL', 'created_at' => null, 'updated_at' => null],
            ['id' => 16, 'business_entity_id' => 2, 'division_id' => 4, 'name' => 'BIGSEMARANG', 'created_at' => null, 'updated_at' => null],
            ['id' => 17, 'business_entity_id' => 3, 'division_id' => 5, 'name' => '-', 'created_at' => null, 'updated_at' => null],
            ['id' => 18, 'business_entity_id' => 1, 'division_id' => 1, 'name' => 'JABO', 'created_at' => null, 'updated_at' => null],
            ['id' => 20, 'business_entity_id' => 2, 'division_id' => 3, 'name' => 'JABO', 'created_at' => '2021-11-16 22:23:46', 'updated_at' => '2021-11-16 22:23:46'],
            ['id' => 21, 'business_entity_id' => 2, 'division_id' => 4, 'name' => 'BIGSOLO', 'created_at' => null, 'updated_at' => '2022-01-07 23:24:00'],
            ['id' => 22, 'business_entity_id' => 2, 'division_id' => 4, 'name' => 'BIGJOGJA', 'created_at' => null, 'updated_at' => '2022-01-07 23:24:09'],
            ['id' => 23, 'business_entity_id' => 2, 'division_id' => 4, 'name' => 'BIGBANDUNG', 'created_at' => null, 'updated_at' => null],
            ['id' => 24, 'business_entity_id' => 2, 'division_id' => 4, 'name' => 'BIGKARAWANG', 'created_at' => null, 'updated_at' => '2022-10-23 17:49:53'],
            ['id' => 25, 'business_entity_id' => 2, 'division_id' => 4, 'name' => 'BIGKARAWANG2', 'created_at' => null, 'updated_at' => null],
            ['id' => 26, 'business_entity_id' => 2, 'division_id' => 4, 'name' => 'BIGPURWOKERTO', 'created_at' => null, 'updated_at' => null],
            ['id' => 27, 'business_entity_id' => 2, 'division_id' => 4, 'name' => 'BIGTASIK', 'created_at' => null, 'updated_at' => null],
            ['id' => 28, 'business_entity_id' => 2, 'division_id' => 2, 'name' => 'JABO1', 'created_at' => '2022-09-12 14:29:02', 'updated_at' => '2022-09-12 14:29:02'],
            ['id' => 29, 'business_entity_id' => 2, 'division_id' => 2, 'name' => 'JABO2', 'created_at' => '2022-09-12 14:29:15', 'updated_at' => '2022-09-12 14:29:15'],
            ['id' => 30, 'business_entity_id' => 2, 'division_id' => 2, 'name' => 'JABAR2', 'created_at' => '2022-09-12 14:29:33', 'updated_at' => '2022-09-12 14:29:33'],
            ['id' => 31, 'business_entity_id' => 2, 'division_id' => 2, 'name' => 'JABAR1', 'created_at' => '2022-09-12 14:29:46', 'updated_at' => '2022-09-12 14:29:46'],
            ['id' => 32, 'business_entity_id' => 2, 'division_id' => 2, 'name' => 'JATENG', 'created_at' => '2022-09-12 14:30:06', 'updated_at' => '2022-09-12 14:30:06'],
            ['id' => 33, 'business_entity_id' => 2, 'division_id' => 2, 'name' => 'JATIM', 'created_at' => '2022-09-13 16:55:09', 'updated_at' => '2022-09-13 16:55:09'],
            ['id' => 34, 'business_entity_id' => 2, 'division_id' => 2, 'name' => 'BALI', 'created_at' => '2022-09-13 16:55:19', 'updated_at' => '2022-09-13 16:55:19'],
            ['id' => 41, 'business_entity_id' => 2, 'division_id' => 2, 'name' => 'JABAR3', 'created_at' => '2022-09-13 17:49:28', 'updated_at' => '2022-09-13 17:49:28'],
            ['id' => 42, 'business_entity_id' => 2, 'division_id' => 2, 'name' => 'LOMBOK', 'created_at' => '2022-09-28 13:56:57', 'updated_at' => '2022-09-28 13:56:57'],
            ['id' => 43, 'business_entity_id' => 2, 'division_id' => 6, 'name' => 'JABAR1', 'created_at' => '2022-09-30 19:23:19', 'updated_at' => '2022-09-30 19:23:19'],
            ['id' => 44, 'business_entity_id' => 2, 'division_id' => 6, 'name' => 'JABAR2', 'created_at' => '2022-09-30 19:24:04', 'updated_at' => '2022-09-30 19:24:04'],
            ['id' => 45, 'business_entity_id' => 2, 'division_id' => 6, 'name' => 'JABAR3', 'created_at' => '2022-09-30 19:24:16', 'updated_at' => '2022-09-30 19:24:16'],
            ['id' => 46, 'business_entity_id' => 2, 'division_id' => 6, 'name' => 'JABAR4', 'created_at' => '2022-09-30 19:24:29', 'updated_at' => '2022-09-30 19:24:29'],
            ['id' => 47, 'business_entity_id' => 2, 'division_id' => 6, 'name' => 'JABO1', 'created_at' => '2022-09-30 19:24:39', 'updated_at' => '2022-09-30 19:24:39'],
            ['id' => 48, 'business_entity_id' => 2, 'division_id' => 6, 'name' => 'JABO2', 'created_at' => '2022-09-30 19:24:47', 'updated_at' => '2022-09-30 19:24:47'],
            ['id' => 49, 'business_entity_id' => 2, 'division_id' => 2, 'name' => 'NTT', 'created_at' => '2022-11-06 13:39:34', 'updated_at' => '2022-11-06 13:39:34'],
            ['id' => 50, 'business_entity_id' => 4, 'division_id' => 7, 'name' => 'JABAR', 'created_at' => '2022-12-04 12:51:59', 'updated_at' => '2022-12-04 12:51:59'],
            ['id' => 51, 'business_entity_id' => 4, 'division_id' => 7, 'name' => 'JABO', 'created_at' => '2022-12-04 12:52:22', 'updated_at' => '2022-12-04 12:52:22'],
            ['id' => 52, 'business_entity_id' => 4, 'division_id' => 7, 'name' => 'JATENG', 'created_at' => '2022-12-04 12:52:31', 'updated_at' => '2022-12-04 12:52:31'],
            ['id' => 54, 'business_entity_id' => 4, 'division_id' => 7, 'name' => 'JATIM', 'created_at' => '2022-12-04 12:52:49', 'updated_at' => '2022-12-04 12:52:49'],
            ['id' => 55, 'business_entity_id' => 4, 'division_id' => 7, 'name' => 'SUMATERA', 'created_at' => '2022-12-04 12:53:00', 'updated_at' => '2022-12-04 12:53:00'],
            ['id' => 56, 'business_entity_id' => 4, 'division_id' => 7, 'name' => 'BALINUSA', 'created_at' => '2022-12-04 12:53:09', 'updated_at' => '2022-12-04 12:53:09'],
            ['id' => 57, 'business_entity_id' => 4, 'division_id' => 7, 'name' => 'KALIMANTAN', 'created_at' => '2022-12-04 12:53:19', 'updated_at' => '2022-12-04 12:53:19'],
            ['id' => 58, 'business_entity_id' => 4, 'division_id' => 7, 'name' => 'SULAWESI', 'created_at' => '2022-12-04 12:53:33', 'updated_at' => '2022-12-04 12:53:33'],
            ['id' => 59, 'business_entity_id' => 4, 'division_id' => 7, 'name' => 'PAPUA', 'created_at' => '2022-12-04 12:53:54', 'updated_at' => '2022-12-04 12:53:54'],
            ['id' => 60, 'business_entity_id' => 4, 'division_id' => 7, 'name' => 'TANGERANGBANTEN', 'created_at' => '2022-12-26 16:01:49', 'updated_at' => '2022-12-26 16:01:49'],
            ['id' => 61, 'business_entity_id' => 2, 'division_id' => 2, 'name' => 'TANGERANGBANTEN', 'created_at' => '2024-01-03 15:24:09', 'updated_at' => '2024-01-03 15:24:09'],
            ['id' => 62, 'business_entity_id' => 2, 'division_id' => 2, 'name' => 'JATIM2', 'created_at' => '2024-03-05 18:25:09', 'updated_at' => '2024-03-05 18:25:09'],
            ['id' => 63, 'business_entity_id' => 5, 'division_id' => 8, 'name' => 'BIGSOLO', 'created_at' => '2024-06-10 10:23:21', 'updated_at' => '2024-06-10 10:23:21'],
            ['id' => 64, 'business_entity_id' => 5, 'division_id' => 8, 'name' => 'BIGSEMARANG', 'created_at' => '2024-06-10 10:26:39', 'updated_at' => '2024-06-10 10:27:14'],
            ['id' => 66, 'business_entity_id' => 5, 'division_id' => 8, 'name' => 'BIGPURWOKERTO', 'created_at' => '2024-06-10 10:29:11', 'updated_at' => '2024-06-10 10:29:11'],
            ['id' => 67, 'business_entity_id' => 5, 'division_id' => 8, 'name' => 'BIGJOGJA', 'created_at' => '2024-06-10 10:29:42', 'updated_at' => '2024-06-10 10:29:42'],
            ['id' => 68, 'business_entity_id' => 5, 'division_id' => 8, 'name' => 'BIGTEGAL', 'created_at' => '2024-06-12 19:23:15', 'updated_at' => '2024-06-12 19:23:15'],
        ]);
    }
}
