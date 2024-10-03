<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Outlet;
use App\Models\Region;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OutletController extends Controller
{
    public function all()
    {
        try {
            $outlet = Outlet::with(['businessEntity', 'cluster', 'region', 'division'])->get();

            return ResponseFormatter::success($outlet, 'Data outlet berhasil diambil');
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 'ERROR', 500);
        }
    }

    /**
     * Fetch.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Outlet::with(['businessEntity', 'cluster', 'region', 'division']);
            
            switch ($user->position_id) {
                case 1: // ASM
                case 6: // COO
                case 8: // CSO
                case 9: // RKAM
                case 11: // CSO FAST EV
                    $division = Division::where('name', $request->divisi)->firstOrFail()->id;
                    $region = Region::where('name', $request->region)
                        ->where('division_id', $division)
                        ->firstOrFail()
                        ->id;
                    $outlet = $query
                        ->where('division_id', $division)
                        ->where('region_id', $region)
                        ->orderBy('name')
                        ->get();
                    break;
                
                case 2: // ASC
                    $outlet = $query
                        ->where('business_entity_id', $user->business_entity_id)
                        ->where('division_id', $user->division_id)
                        ->where('region_id', $user->region_id)
                        ->whereIn('cluster_id', [$user->cluster_id, $user->cluster_id2])
                        ->orderBy('name')
                        ->get();
                    break;
                
                case 3: // DSF/DM
                case 10: // KAM
                    $outlet = $query
                        ->where('business_entity_id', $user->business_entity_id)
                        ->where('division_id', $user->division_id)
                        ->where('region_id', $user->region_id)
                        ->where('cluster_id', $user->cluster_id)
                        ->orderBy('name')
                        ->get();
                    break;

                default:
                    $outlet = Outlet::with(['businessEntity', 'cluster', 'region', 'division'])->get();
                    break;
            }

            return ResponseFormatter::success($outlet, 'Data outlet berhasil diambil');
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 'ERROR', 500);
        }
    }

    /**
     * singleOutlet.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function singleOutlet(Request $request, $code)
    {
        try {
            $outlet = Outlet::with(['businessEntity', 'cluster', 'region', 'division'])
                ->where('code', $code)
                ->firstOrFail();

            return ResponseFormatter::success($outlet, 'Data outlet berhasil diambil');
        } catch (Exception $err) {
            return ResponseFormatter::error(null, 'Outlet tidak ditemukan', 404);
        }
    }

    /**
     * updateFoto.
     * @requestMediaType multipart/form-data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatefoto(Request $request)
    {
        try {
            $request->validate([
                'code' => ['required', 'string'],
                'owner' => ['nullable', 'string'],
                'telp' => ['nullable', 'string'],
                'photo_front' => ['nullable', 'string'],
                'photo_right' => ['nullable', 'string'],
                'photo_left' => ['nullable', 'string'],
                'photo_ktp' => ['nullable', 'string'],
                'video' => ['nullable', 'string'],
            ]);

            $data = Outlet::where('code', $request->code)->firstOrFail();

            // Mengolah foto
            if ($request->hasFile('photo0') || $request->hasFile('video')) {
                foreach ($request->files as $fileKey => $file) {
                    $fileName = $file->getClientOriginalName();
                    if (Str::contains($fileName, 'fotodepan')) {
                        $data->photo_front = $fileName;
                    } elseif (Str::contains($fileName, 'fotokanan')) {
                        $data->photo_right = $fileName;
                    } elseif (Str::contains($fileName, 'fotokiri')) {
                        $data->photo_left = $fileName;
                    } elseif (Str::contains($fileName, 'fotoktp')) {
                        $data->photo_ktp = $fileName;
                    } elseif ($request->hasFile('video')) {
                        $data->video = 'update-' . now()->timestamp . '-' . $fileName;
                    }

                    $file->move(storage_path('app/public/'), $fileName);
                }
            }

            // Update data outlet
            $data->owner = strtoupper($request->owner ?? $data->owner);
            $data->telp = $request->telp ?? $data->telp;
            $data->latlong = $request->latlong ?? $data->latlong;
            $data->save();

            return ResponseFormatter::success($data, 'Outlet berhasil diperbarui');
        } catch (Exception $e) {
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }
}
