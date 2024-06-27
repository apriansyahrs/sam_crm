<?php

namespace App\Imports;

use App\Models\BusinessEntity;
use App\Models\Cluster;
use App\Models\Division;
use App\Models\Outlet;
use App\Models\Region;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OutletImport implements ToModel,WithHeadingRow
{
    public function model(array $row)
    {
	    $badanusaha_id = BusinessEntity::where('name', preg_replace('/\s+/', '', $row['badan_usaha']))->first()->id;
        $divisi_id = Division::where('name', preg_replace('/\s+/', '', $row['divisi']))->where('business_entity_id', $badanusaha_id)->first()->id;
        $region_id = Region::where('name', preg_replace('/\s+/', '', $row['region']))->where('division_id', $divisi_id)->where('business_entity_id', $badanusaha_id)->first()->id;
        $cluster_id = Cluster::where('name', preg_replace('/\s+/', '', $row['cluster']))->first()->id;
	    $outlet = new Outlet();
        $outlet = $outlet->where('code', preg_replace('/\s+/', '', strtoupper($row['kode_outlet'])))->where('division_id',$divisi_id);
        if ($outlet->first())
        {
            $outlet->update([
                'business_entity_id' => $badanusaha_id,
                'division_id' => $divisi_id,
                'region_id' => $region_id,
                'cluster_id' =>$cluster_id,
                'code' => preg_replace('/\s+/', '', strtoupper($row['kode_outlet'])),
                'name' => strtoupper($row['nama_outlet']),
                'address' => strtoupper($row['alamat_outlet']),
                'district' => strtoupper($row['distric']),
                'status' => strtoupper($row['status']),
                'radius' => $row['radius'] ?? $outlet->first()->radius,
                'limit' => $row['limit'] ?? $outlet->first()->limit,
                'latlong' => $row['latlong'] ?? $outlet->first()->latlong,
            ]);
        }
        else
        {
            $badanusaha_id = BusinessEntity::where('name', preg_replace('/\s+/', '', $row['badan_usaha']))->first()->id;
            $divisi_id = Division::where('name', preg_replace('/\s+/', '', $row['divisi']))->where('business_entity_id', $badanusaha_id)->first()->id;
            $region_id = Region::where('name', preg_replace('/\s+/', '', $row['region']))->where('division_id', $divisi_id)->where('business_entity_id', $badanusaha_id)->first()->id;
            return new Outlet([
                'business_entity_id' => $badanusaha_id,
                'division_id' => $divisi_id,
                'region_id' => $region_id,
                'cluster_id' => $cluster_id,
                'code' => strtoupper($row['kode_outlet']),
                'name' => strtoupper($row['nama_outlet']),
                'address' => strtoupper($row['alamat_outlet']),
                'district' => strtoupper($row['district']),
                'status' => strtoupper($row['status']),
                'radius' => $row['radius'] ?? 0,
                'limit' => $row['limit'] ?? 0,
                'latlong' => $row['latlong'],
            ]);
        }
    }
}
