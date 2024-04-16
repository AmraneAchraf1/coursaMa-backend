<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\TaxiQeueeController;
use App\Http\Controllers\api\TaxiStationController;
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


// soft delete
Route::get('taxi-queue-deleted', [TaxiQeueeController::class, 'getDeleted'])->middleware('auth:sanctum');
Route::put('taxi-queue-restore/{id}', [TaxiQeueeController::class, 'restoreDeleted'])->middleware('auth:sanctum');
Route::delete('taxi-queue-force-delete/{id}', [TaxiQeueeController::class, 'deletePermanently'])->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('user', [AuthController::class, 'user'])->middleware('auth:sanctum');
Route::post('user-update', [AuthController::class, 'update'])->middleware('auth:sanctum');


Route::apiResource('taxi-stations', TaxiStationController::class)->middleware('auth:sanctum');
Route::apiResource('taxi-queue', TaxiQeueeController::class)->middleware('auth:sanctum');
