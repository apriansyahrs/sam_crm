<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Noo;
use App\Models\User;
use App\Models\Outlet;
use App\Models\Region;
use App\Models\Cluster;
use App\Models\Division;
use App\Helpers\SendNotif;
use App\Models\BadanUsaha;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NooController extends Controller
{
    /**
     * Fetch leads based on user role and filters.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch(Request $request)
    {
        try {
            $user = Auth::user();
            $businessEntityId = $user->business_entity_id;
            $divisionId = $user->division_id;
            $regionId = $user->region_id;
            $clusterId = $user->cluster_id;
            $roleId = $user->role_id;

            // Base query with relationships
            $query = Noo::with(['businessEntity', 'cluster', 'region', 'division']);

            switch ($roleId) {
                case 1: // ASM Role
                    if ($user->id === 158) { // Special case for user ID 158
                        $noos = $query
                            ->whereIn('region_id', [13, 27, 26, 23, 24])
                            ->where('division_id', 4)
                            ->latest()
                            ->get();
                    } else {
                        $noos = $query
                            ->where('tm_id', $user->id)
                            ->latest()
                            ->get();
                    }
                    break;

                case 2: // ASC Role
                    $noos = $query
                        ->where('business_entity_id', $businessEntityId)
                        ->where('division_id', $divisionId)
                        ->where('region_id', $regionId)
                        ->latest()
                        ->get();
                    break;

                case 3: // DSF/DM Role
                    $noos = $query
                        ->where('business_entity_id', $businessEntityId)
                        ->where('division_id', $divisionId)
                        ->where('region_id', $regionId)
                        ->where('cluster_id', $clusterId)
                        ->orderBy('updated_at', 'DESC')
                        ->get();
                    break;

                case 6: // COO Role
                    $noos = $query
                        ->latest()
                        ->get();
                    break;

                case 8: // CSO Role
                    $noos = $query
                        ->where('division_id', 4)
                        ->latest()
                        ->get();
                    break;

                case 9: // RKAM Role
                    $noos = $query
                        ->where('tm_id', $user->id)
                        ->latest()
                        ->get();
                    break;

                case 10: // KAM Role
                    $noos = $query
                        ->where('business_entity_id', $businessEntityId)
                        ->where('division_id', $divisionId)
                        ->where('region_id', $regionId)
                        ->latest()
                        ->get();
                    break;

                case 11: // CSO FAST EV Role
                    $noos = $query
                        ->where('division_id', 7)
                        ->latest()
                        ->get();
                    break;

                default: // Default role with specific filtering
                    $noos = Noo::with(['businessEntity', 'cluster', 'region', 'division'])
                        ->whereIn('business_entity_id', [2, 4])
                        ->whereIn('status', ['PENDING', 'CONFIRMED', 'REJECTED'])
                        ->latest()
                        ->get();
                    break;
            }

            return ResponseFormatter::success($noos, 'Fetch Noo success.');
        } catch (Exception $err) {
            return ResponseFormatter::error(['message' => $err->getMessage()], 'Something went wrong', 500);
        }
    }

    /**
     * Fetch all leads.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        try {
            $noos = Noo::with(['businessEntity', 'cluster', 'region', 'division'])->get();

            return ResponseFormatter::success($noos, 'Fetch Noo success.');
        } catch (Exception $err) {
            return ResponseFormatter::error(['message' => $err->getMessage()], 'Something went wrong', 500);
        }
    }

    /**
     * Handle new NOO submission.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(Request $request)
    {
        try {
            $user = Auth::user();
            $data = [
                'name' => $request->nama_outlet,
                'address' => $request->alamat_outlet,
                'owner' => $request->nama_pemilik,
                'phone' => $request->nomer_pemilik,
                'optional_phone' => $request->nomer_perwakilan,
                'ktp_outlet' => $request->ktpnpwp,
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
            ];

            // Handle different roles and assign related IDs
            switch ($user->role_id) {
                case 1: // ASM Role
                case 9: // RKAM Role
                    $businessEntityId = BusinessEntity::where('name', $request->bu)->first()->id;
                    $divisionId = Division::where('business_entity_id', $businessEntityId)->where('name', $request->div)->first()->id;
                    $regionId = Region::where('business_entity_id', $businessEntityId)->where('division_id', $divisionId)->where('name', $request->reg)->first()->id;
                    $clusterId = Cluster::where('business_entity_id', $businessEntityId)->where('division_id', $divisionId)->where('region_id', $regionId)->where('name', $request->clus)->first()->id;
                    $data['business_entity_id'] = $businessEntityId;
                    $data['division_id'] = $divisionId;
                    $data['region_id'] = $regionId;
                    $data['cluster_id'] = $clusterId;
                    break;

                case 2: // ASC Role
                case 10: // KAM Role
                    $data['business_entity_id'] = $user->business_entity_id;
                    $data['division_id'] = $user->division_id;
                    $data['region_id'] = $user->region_id;
                    $data['cluster_id'] = Cluster::where('business_entity_id', $user->business_entity_id)
                        ->where('division_id', $user->division_id)
                        ->where('region_id', $user->region_id)
                        ->where('name', $request->clus)
                        ->first()
                        ->id;
                    break;

                default:
                    $data['business_entity_id'] = $user->business_entity_id;
                    $data['division_id'] = $user->division_id;
                    $data['region_id'] = $user->region_id;
                    $data['cluster_id'] = $user->cluster_id;
                    break;
            }

            // Handle photo uploads
            for ($i = 0; $i <= 4; $i++) {
                if ($request->hasFile('photo' . $i)) {
                    $file = $request->file('photo' . $i);
                    $namaFoto = $file->getClientOriginalName();

                    if (Str::contains($namaFoto, 'fotodepan')) {
                        $data['photo_front'] = $namaFoto;
                    } elseif (Str::contains($namaFoto, 'fotokanan')) {
                        $data['photo_right'] = $namaFoto;
                    } elseif (Str::contains($namaFoto, 'fotokiri')) {
                        $data['photo_left'] = $namaFoto;
                    } elseif (Str::contains($namaFoto, 'fotoktp')) {
                        $data['photo_ktp'] = $namaFoto;
                    } else {
                        $data['photo_shop_sign'] = $namaFoto;
                    }
                    $file->move(storage_path('app/public/'), $namaFoto);
                }
            }

            // Handle video upload
            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $name = 'noo-' . time() . $video->getClientOriginalName();
                $data['video'] = $name;
                $video->move(storage_path('app/public/'), $name);
            }

            // Notification IDs based on role
            $notifId = [];
            switch ($user->role_id) {
                case 1:
                case 9:
                    array_push($notifId, User::where('role_id', 4)->first()->id_notif);
                    break;
                case 2:
                case 10:
                    array_push($notifId, User::where('role_id', 4)->first()->id_notif);
                    array_push($notifId, $user->tm->id_notif);
                    break;
                default:
                    array_push($notifId, User::where('role_id', 4)->first()->id_notif);
                    array_push($notifId, $user->tm->id_notif);
                    $ascNotif = User::where('role_id', 2)->where('division_id', $user->division_id)->where('region_id', $user->region_id)->first()->id_notif ?? null;
                    if ($ascNotif) {
                        array_push($notifId, $ascNotif);
                    }
                    break;
            }

            // Create the NOO entry
            $insert = Noo::create($data);
            if ($insert && !empty($notifId)) {
                SendNotif::sendMessage('Noo baru ' . $request->nama_outlet . ' ditambahkan oleh ' . Auth::user()->nama_lengkap, $notifId);
            }

            return ResponseFormatter::success(null, 'Berhasil menambahkan NOO ' . $request->nama_outlet);
        } catch (Exception $e) {
            error_log($e);
            return ResponseFormatter::error($e->getMessage(), 'Gagal menambahkan NOO');
        }
    }

    public function confirm(Request $request)
    {
        try {

            $request->validate([
                'id' => ['required'],
                'status' => ['required'],
                'limit' => ['required'],
                'kode_outlet' => ['required'],
            ]);

            $noo = Noo::findOrFail($request->id);
            $noo->status = $request->status;
            $noo->limit = $request->limit;
            $noo->kode_outlet = $request->kode_outlet;
            $noo->confirmed_by = Auth::user()->nama_lengkap;
            $noo->confirmed_at = now();
            $noo->update();
            SendNotif::sendMessage(
                'Noo ' . $noo->nama_outlet . ' sudah di konfirmasi oleh ' .
                    Auth::user()->nama_lengkap . PHP_EOL .
                    'Dengan limit : Rp ' . number_format($request->limit, 0, ',', '.'),
                array(User::where('nama_lengkap', $noo->created_by)->first()->id_notif ?? '-', $noo->tm->id_notif)

            );
            return ResponseFormatter::success($noo, 'berhasil update');
        } catch (Exception $e) {
            error_log($e);
            return ResponseFormatter::error($e, 'gagal');
        }
    }

    public function approved(Request $request)
    {
        try {

            $request->validate([
                'id' => ['required'],
                'status' => ['required'],
            ]);

            $noo = Noo::find($request->id);
            $noo->status = $request->status;
            $noo->approved_by = Auth::user()->nama_lengkap;
            $noo->approved_at = now();
            $noo->update();

            $notif = array();
            $register = User::where('nama_lengkap', $noo->created_by)->first()->id_notif;
            if ($register) {
                array_push($notif, $register);
            }

            $data = [
                'kode_outlet' => $noo->kode_outlet,
                'badanusaha_id' => $noo->badanusaha_id,
                'nama_outlet' => $noo->nama_outlet,
                'divisi_id' => $noo->divisi_id,
                'alamat_outlet' => $noo->alamat_outlet,
                'nama_pemilik_outlet' => $noo->nama_pemilik_outlet,
                'nomer_tlp_outlet' => $noo->nomer_tlp_outlet,
                'distric' => $noo->distric,
                'region_id' => $noo->region_id,
                'cluster_id' => $noo->cluster_id,
                'poto_shop_sign' => $noo->poto_shop_sign,
                'poto_depan' => $noo->poto_depan,
                'poto_kanan' => $noo->poto_kanan,
                'poto_kiri' => $noo->poto_kiri,
                'poto_ktp' => $noo->poto_ktp,
                'video' => $noo->video,
                'radius' => 0,
                'latlong' => $noo->latlong,
                'status_outlet' => 'MAINTAIN',
                'limit' => $noo->limit,
            ];
            $outletExisting = Outlet::where('badanusaha_id', $noo->badanusaha_id)
                ->where('divisi_id', $noo->divisi_id)
                ->where('region_id', $noo->region_id)
                ->where('cluster_id', $noo->cluster_id)
                ->where('kode_outlet', $noo->kode_outlet)->first();
            if ($outletExisting) {
                return ResponseFormatter::success($noo, 'berhasil update');
            } else {
                $insert = Outlet::create($data);
            }
            if (count($notif) != 0 && $insert) {
                SendNotif::sendMessage(
                    'Noo ' . $noo->nama_outlet . ' sudah di setujui oleh ' .
                        Auth::user()->nama_lengkap,
                    $notif
                );
            }
            return ResponseFormatter::success($noo, 'berhasil update');
        } catch (Exception $e) {
            error_log($e);
            return ResponseFormatter::error($e, 'gagal');
        }
    }

    public function reject(Request $request)
    {
        try {
            $request->validate([
                'id' => ['required'],
                'status' => ['required'],
                'alasan' => ['required'],
            ]);

            $noo = Noo::findOrFail($request->id);
            $noo->status = $request->status;
            $noo->keterangan = $request->alasan;
            $noo->rejected_by = Auth::user()->nama_lengkap;
            $noo->rejected_at = now();

            $noo->update();

            SendNotif::sendMessage('Noo ' . $noo->nama_outlet . ' ditolak oleh ' . Auth::user()->nama_lengkap . PHP_EOL . 'Alasan : ' . $request->alasan, array($noo->tm->id_notif));

            return ResponseFormatter::success($noo, 'berhasil update');
        } catch (Exception $e) {
            return ResponseFormatter::error($e, 'gagal');
        }
    }

    public function getbu(Request $request)
    {
        try {
            $badanusahas = BadanUsaha::all();
            return ResponseFormatter::success($badanusahas, 'berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error([], $e->getMessage());
        }
    }

    public function getdiv(Request $request)
    {
        try {
            $badanusaha_id = BadanUsaha::where('name', $request->bu)->first()->id;
            $divisi = Division::where('badanusaha_id', $badanusaha_id)->get();
            return ResponseFormatter::success($divisi, 'berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error([], $e->getMessage());
        }
    }

    public function getreg(Request $request)
    {
        try {
            $badanusaha_id = BadanUsaha::where('name', $request->bu)->first()->id;
            $divisi_id = Division::where('badanusaha_id', $badanusaha_id)->where('name', $request->div)->first()->id;
            $region = Region::where('badanusaha_id', $badanusaha_id)->where('divisi_id', $divisi_id)->get();
            return ResponseFormatter::success($region, 'berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error([], $e->getMessage());
        }
    }

    public function getclus(Request $request)
    {
        try {
            if ($request->role) {
                $user = Auth::user();
                $cluster = Cluster::where('badanusaha_id', $user->badanusaha_id)->where('divisi_id', $user->divisi_id)->where('region_id', $user->region_id)->get();
            } else {
                $badanusaha_id = BadanUsaha::where('name', $request->bu)->first()->id;
                $divisi_id = Division::where('badanusaha_id', $badanusaha_id)->where('name', $request->div)->first()->id;
                $region_id = Region::where('badanusaha_id', $badanusaha_id)->where('divisi_id', $divisi_id)->where('name', $request->reg)->first()->id;
                $cluster = Cluster::where('badanusaha_id', $badanusaha_id)->where('divisi_id', $divisi_id)->where('region_id', $region_id)->get();
            }
            return ResponseFormatter::success($cluster, 'berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), $e->getMessage());
        }
    }

    public function tesgetclus(Request $request)
    {
        try {
            if ($request->role) {
                $user = Auth::user();
                $cluster = Cluster::where('badanusaha_id', $user->badanusaha_id)->where('divisi_id', $user->divisi_id)->where('region_id', $user->region_id)->get();
                dd($cluster);
            } else {
                $badanusaha_id = BadanUsaha::where('name', $request->bu)->first()->id;
                $divisi_id = Division::where('badanusaha_id', $badanusaha_id)->where('name', $request->div)->first()->id;
                $region_id = Region::where('badanusaha_id', $badanusaha_id)->where('divisi_id', $divisi_id)->where('name', $request->reg)->first()->id;
                $cluster = Cluster::where('badanusaha_id', $badanusaha_id)->where('divisi_id', $divisi_id)->where('region_id', $region_id)->get();
            }
            return ResponseFormatter::success($cluster, 'berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), $e->getMessage());
        }
    }

    public function getnoooutlet(Request $request)
    {
        try {
            $user = Auth::user();
            $badanusahaId = $user->badanusaha_id;
            $divisiId = $user->divisi_id;
            $regionId = $user->region_id;
            $clusterId = $user->cluster_id;
            $roleId = $user->role_id;

            $query = Noo::with(['badanusaha', 'cluster', 'region', 'divisi'])->where('approved_by', null);

            switch ($roleId) {
                #ASM
                case 1:
                    $noos = $query
                        ->where('tm_id', $user->id)
                        ->orderBy('nama_outlet')
                        ->get();
                    break;
                #ASC
                case 2:
                    $noos = $query
                        ->where('badanusaha_id', $badanusahaId)
                        ->where('divisi_id', $divisiId)
                        ->where('region_id', $regionId)
                        ->orderBy('nama_outlet')
                        ->get();
                    break;
                #DSF/DM
                case 3:
                    $noos = $query
                        ->where('badanusaha_id', $badanusahaId)
                        ->where('divisi_id', $divisiId)
                        ->where('region_id', $regionId)
                        ->where('cluster_id', $clusterId)
                        ->orderBy('nama_outlet')
                        ->get();
                    break;

                default:
                    $noos = Noo::with(['badanusaha', 'cluster', 'region', 'divisi'])->where('badanusaha_id',2)->orWhere('badanusaha_id',4)->whereIn('status',['PENDING','CONFIRMED','REJECTED'])->latest()->get();
                    break;
            }

            return ResponseFormatter::success(
                $noos,
                'fetch noo success',
            );
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => $err,
            ], 'something wrong', 500);
        }
    }

    public function singleOutlet(Request $request, $kodeOutlet)
    {
        //dd($request->all());
        try {
            $noo = Noo::with(['badanusaha', 'cluster', 'region', 'divisi'])
                ->where('id', $kodeOutlet)
                ->get();
            return ResponseFormatter::success($noo, 'berhasil');
        } catch (Exception $err) {
            return ResponseFormatter::error(null, 'ada kesalahan');
        }
    }
}
