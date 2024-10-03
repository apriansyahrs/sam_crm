<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Noo;
use App\Models\User;
use App\Models\Region;
use App\Models\Cluster;
use App\Models\Division;
use App\Helpers\SendNotif;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\BusinessEntity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LeadController extends Controller
{
    /**
     * Create a new lead.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $user = Auth::user();
            $data = [
                'name' => $request->nama_outlet,
                'address' => $request->alamat_outlet,
                'owner' => $request->nama_pemilik,
                'phone' => $request->nomer_pemilik,
                'optional_phone' => $request->nomer_perwakilan,
                'ktp_outlet' => '-', // Default value for KTP Outlet
                'district' => $request->distric,
                'oppo' => $request->oppo,
                'vivo' => $request->vivo,
                'samsung' => $request->samsung,
                'xiaomi' => $request->xiaomi,
                'realme' => $request->realme,
                'fl' => $request->fl,
                'latlong' => $request->latlong,
                'created_by' => $user->nama_lengkap,
                'tm_id' => $user->tm->id,
                'notes' => "LEAD",
                'photo_ktp' => "-", // Default value for Photo KTP
            ];

            // Handle different user roles
            switch ($user->role_id) {
                case 1: // Super Admin
                    $badanusaha_id = BusinessEntity::where('name', $request->bu)->first()->id;
                    $division_id = Division::where('business_entity_id', $badanusaha_id)->where('name', $request->div)->first()->id;
                    $region_id = Region::where('business_entity_id', $badanusaha_id)->where('division_id', $division_id)->where('name', $request->reg)->first()->id;
                    $cluster_id = Cluster::where('business_entity_id', $badanusaha_id)->where('division_id', $division_id)->where('region_id', $region_id)->where('name', $request->clus)->first()->id;
                    $data['business_entity_id'] = $badanusaha_id;
                    $data['division_id'] = $division_id;
                    $data['region_id'] = $region_id;
                    $data['cluster_id'] = $cluster_id;
                    break;

                case 2: // Regional Manager
                    $data['business_entity_id'] = $user->business_entity_id;
                    $data['division_id'] = $user->division_id;
                    $data['region_id'] = $user->region_id;
                    $data['cluster_id'] = Cluster::where('business_entity_id', $user->business_entity_id)
                        ->where('division_id', $user->division_id)
                        ->where('region_id', $user->region_id)
                        ->where('name', $request->clus)
                        ->first()->id;
                    break;

                default: // Default for other roles
                    $data['business_entity_id'] = $user->business_entity_id;
                    $data['division_id'] = $user->division_id;
                    $data['region_id'] = $user->region_id;
                    $data['cluster_id'] = $user->cluster_id;
                    break;
            }

            // Handling photo uploads
            for ($i = 0; $i <= 3; $i++) {
                if ($request->hasFile('photo' . $i)) {
                    $photo = $request->file('photo' . $i);
                    $namaFoto = $photo->getClientOriginalName();
                    
                    if (Str::contains($namaFoto, 'fotodepan')) {
                        $data['photo_front'] = $namaFoto;
                    } elseif (Str::contains($namaFoto, 'fotokanan')) {
                        $data['photo_right'] = $namaFoto;
                    } elseif (Str::contains($namaFoto, 'fotokiri')) {
                        $data['photo_left'] = $namaFoto;
                    } else {
                        $data['photo_shop_sign'] = $namaFoto;
                    }
                    
                    $photo->move(storage_path('app/public/'), $namaFoto);
                }
            }

            // Handle video upload
            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $name = 'noo-' . time() . $video->getClientOriginalName();
                $data['video'] = $name;
                $video->move(storage_path('app/public/'), $name);
            }

            // Create a new Noo entry
            $insert = Noo::create($data);
            return ResponseFormatter::success(null, 'Berhasil menambahkan LEAD ' . $request->nama_outlet);
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), $e->getMessage());
        }
    }

    /**
     * Update a lead.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'id' => ['required'],
                'noktp' => ['required'],
            ]);

            $lead = Noo::findOrFail($request->id);

            // Update photo if provided
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $namaFoto = $photo->getClientOriginalName();
                $photo->move(storage_path('app/public/'), $namaFoto);
                $lead->update(['photo_ktp' => $namaFoto]);
            }

            // Update KTP number and clear notes
            $lead->update([
                'ktp_outlet' => $request->noktp,
                'notes' => null,
            ]);

            // Send notification
            SendNotif::sendMessage('Noo baru ' . $lead->name . ' ditambahkan oleh ' . Auth::user()->nama_lengkap, [User::where('role_id', 4)->first()->id_notif]);

            return ResponseFormatter::success(null, 'Berhasil memperbarui Lead ' . $request->nama_outlet);
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), $e->getMessage());
        }
    }
}
