<?php

namespace App\Imports;

use App\Models\BusinessEntity;
use App\Models\Cluster;
use App\Models\Division;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    // public function collection(Collection $collection)
    // {
    //     //
    // }
    public function model(array $row)
    {
        $user = new User();
        $user = $user->where('username', strtolower($row['username']));
        if ($user->first()) {
            error_log($row['name']);
            $badanusaha_id = BusinessEntity::where('name', preg_replace('/\s+/', '', $row['badan_usaha']))->first()->id;
            $divisi_id = Division::where('name', preg_replace('/\s+/', '', $row['divisi']))->where('business_entity_id', $badanusaha_id)->first()->id;
            $region_id = Region::where('name', preg_replace('/\s+/', '', $row['region']))->where('division_id', $divisi_id)->where('business_entity_id', $badanusaha_id)->first()->id;
            $user->update([
                'name' => strtoupper($row['nama_lengkap']),
                'username' => strtolower($row['username']),
                // 'role_id' => Role::where('name', preg_replace('/\s+/', '', $row['role']))->first()->id,
                'business_entity_id' => $badanusaha_id,
                'division_id' => $divisi_id,
                'region_id' => Region::where('name', preg_replace('/\s+/', '', $row['region']))->first()->id,
                'cluster_id' => Cluster::where('name', preg_replace('/\s+/', '', $row['cluster']))->first()->id,
                'tm_id' => User::where('name', $row['tm'])->first()->id,
            ]);
        } else {
            $badanusaha_id = BusinessEntity::where('name', preg_replace('/\s+/', '', $row['badan_usaha']))->first()->id;
            $divisi_id = Division::where('name', preg_replace('/\s+/', '', $row['divisi']))->where('business_entity_id', $badanusaha_id)->first()->id;
            $region_id = Region::where('name', preg_replace('/\s+/', '', $row['region']))->where('division_id', $divisi_id)->where('business_entity_id', $badanusaha_id)->first()->id;
            return new User([
                'name' => strtoupper($row['nama_lengkap']),
                'username' => strtolower($row['username']),
                // 'role_id' => Role::where('name', preg_replace('/\s+/', '', $row['role']))->first()->id,
                'business_entity_id' => $badanusaha_id,
                'division_id' => $divisi_id,
                'region_id' => Region::where('name', preg_replace('/\s+/', '', $row['region']))->first()->id,
                'cluster_id' => Cluster::where('name', preg_replace('/\s+/', '', $row['cluster']))->first()->id,
                'tm_id' => User::where('name', $row['tm'])->first()->id ?? null,
                'password' => $row['password'] ? bcrypt($row['password']) : bcrypt('complete123'),
            ]);
        }
    }
}
