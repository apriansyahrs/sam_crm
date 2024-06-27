<?php

namespace App\Imports;

use App\Models\BusinessEntity;
use App\Models\Cluster;
use App\Models\Division;
use App\Models\Position;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $username = strtolower($row['username']);
        $user = User::where('username', $username)->first();

        $badanUsahaId = BusinessEntity::where('name', preg_replace('/\s+/', '', $row['badan_usaha']))->first()->id;
        $divisiId = Division::where('name', preg_replace('/\s+/', '', $row['divisi']))
            ->where('business_entity_id', $badanUsahaId)
            ->first()
            ->id;
        $positionName = preg_replace('/\s+/', '', $row['posisi']);
        $positionId = Position::where('name', $positionName)->first()->id;
        $regionId = strtoupper($row['posisi']) === 'ASM' ? null : Region::where('name', preg_replace('/\s+/', '', $row['region']))
            ->where('division_id', $divisiId)
            ->where('business_entity_id', $badanUsahaId)
            ->first()
            ->id;
        $clusterId = Cluster::where('name', preg_replace('/\s+/', '', $row['cluster']))->first()->id;
        $tmId = User::where('name', $row['tm'])->first()->id ?? null;

        if ($user) {
            error_log($row['nama'] . $row['posisi']);

            $user->update([
                'name' => strtoupper($row['nama']),
                'username' => $username,
                'position_id' => $positionId,
                'business_entity_id' => $badanUsahaId,
                'division_id' => $divisiId,
                'region_id' => strtoupper($row['posisi']) === 'ASM' ? Region::where('name', preg_replace('/\s+/', '', $row['region']))->first()->id : $regionId,
                'cluster_id' => $clusterId,
                'tm_id' => $tmId,
            ]);
        } else {
            return new User([
                'name' => strtoupper($row['nama']),
                'username' => $username,
                'position_id' => $positionId,
                'business_entity_id' => $badanUsahaId,
                'division_id' => $divisiId,
                'region_id' => strtoupper($row['posisi']) === 'ASM' ? Region::where('name', preg_replace('/\s+/', '', $row['region']))->first()->id : $regionId,
                'cluster_id' => $clusterId,
                'tm_id' => $tmId,
                'password' => $row['password'] ? bcrypt($row['password']) : bcrypt('complete123'),
            ]);
        }
    }
}
