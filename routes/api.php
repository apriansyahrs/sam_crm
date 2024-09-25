<?php

use App\Http\Controllers\API\LeadController;
use App\Http\Controllers\API\NooController;
use App\Http\Controllers\API\OutletController;
use App\Http\Controllers\API\PlanVisitController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VisitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    //USER
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('logout', [UserController::class, 'logout']);

    //OUTLET
    Route::get('outlet', [OutletController::class, 'fetch']);
    Route::get('outlet/{nama}', [OutletController::class, 'singleOutlet']);
    Route::post('outlet', [OutletController::class, 'updatefoto']);

    //VISIT
    Route::get('visit', [VisitController::class, 'fetch']);
    Route::get('visit/check', [VisitController::class, 'check']);
    Route::post('visit', [VisitController::class, 'submit']);
    Route::get('visit/monitor', [VisitController::class, 'monitor']);
    Route::post('visitNoo', [VisitController::class, 'submitNoo']);

    //PLANVISIT
    Route::get('planvisit', [PlanVisitController::class, 'fetch']);
    Route::post('planvisit', [PlanVisitController::class, 'add']);
    Route::get('planvisit/filter', [PlanVisitController::class, 'bymonth']);
    Route::delete('planvisit', [PlanVisitController::class, 'delete']);
    Route::delete('planvisitrealme', [PlanVisitController::class, 'deleterealme']);
    Route::get('planvisitnoo', [PlanVisitController::class, 'fetchnoo']);
    Route::delete('planvisitnoo', [PlanVisitController::class, 'deletenoo']);

    //NOO
    Route::get('noo/getbu', [NooController::class, 'getbu']);
    Route::get('noo/getdiv', [NooController::class, 'getdiv']);
    Route::get('noo/getreg', [NooController::class, 'getreg']);
    Route::get('noo/getclus', [NooController::class, 'getclus']);
    Route::post('noo', [NooController::class, 'submit']);
    Route::get('noo/all', [NooController::class, 'all']);
    Route::get('noo', [NooController::class, 'fetch']);
    Route::get('noo/{kodeOutlet}', [NooController::class, 'singleOutlet']);
    Route::get('nooOutlet', [NooController::class, 'getnoooutlet']);

    Route::post('noo/confirm', [NooController::class, 'confirm']);
    Route::post('noo/approved', [NooController::class, 'approved']);
    Route::post('noo/reject', [NooController::class, 'reject']);

    //LEAD
    Route::post('lead', [LeadController::class, 'create']);
    Route::post('lead/update', [LeadController::class, 'update']);
});
