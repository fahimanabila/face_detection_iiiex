<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AbsensiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// * CRUD user dan absensi
// Route::resource('user', UserController::class);
// Route::resource('absensi', AbsensiController::class);

// * POST ABSEN jika wajah user terdeteksi
Route::post('absen', [AbsensiController::class, 'store']);

// * GET data nama dan nim user
Route::get('usernim', [UserController::class, 'user_nim']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
