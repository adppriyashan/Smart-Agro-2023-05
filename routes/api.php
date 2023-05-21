<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CarParkController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register',[AuthController::class,'register']);
Route::post('/auth/login',[AuthController::class,'login']);
Route::post('/data/parkings',[CarParkController::class,'getCarParkings']);
Route::post('/data/slots',[CarParkController::class,'getCarParkingSlots']);
Route::post('/data/parking/slots',[CarParkController::class,'getCarParkingWithSlots']);
Route::post('/data/parking/info',[CarParkController::class,'getUserParkingInformations']);
Route::post('/data/parking/history',[CarParkController::class,'getUserParkingHistory']);

Route::post('/sale/enroll',[SaleController::class,'enrollAPI']);
Route::post('/sale/scanqr',[SaleController::class,'arrivedAPI']);
Route::post('/vehicle/leave',[SaleController::class,'makeLeaveAPI']); //slotid (id)
Route::post('/vehicle/emergency',[SaleController::class,'makeEmergencyAPI']); //record id (id)
Route::post('/feedback/enroll',[FeedbackController::class,'enroll']);
