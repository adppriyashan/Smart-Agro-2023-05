<?php

use App\Http\Controllers\API\CarParkController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTypeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(['auth', 'permitted']);

Route::get('/logout', [UserController::class, 'logout'])->name('admin.logout');

Route::prefix('/users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->middleware(['auth', 'permitted']);
    Route::post('/enroll', [UserController::class, 'enroll'])->name('admin.users.enroll')->middleware(['auth']);
    Route::get('/list', [UserController::class, 'list'])->name('admin.users.list')->middleware(['auth']);
    Route::get('/get', [UserController::class, 'getOne'])->name('admin.users.get.one')->middleware(['auth']);
    Route::get('/delete', [UserController::class, 'deleteOne'])->name('admin.users.delete.one')->middleware(['auth']);
    Route::get('/find', [UserController::class, 'find'])->name('admin.users.find.one')->middleware(['auth']);
});

Route::prefix('/usertypes')->group(function () {
    Route::get('/', [UserTypeController::class, 'index'])->middleware(['auth', 'permitted']);
    Route::post('/enroll', [UserTypeController::class, 'enroll'])->name('admin.usertypes.enroll')->middleware(['auth']);
    Route::get('/list', [UserTypeController::class, 'list'])->name('admin.usertypes.list')->middleware(['auth']);
    Route::get('/get', [UserTypeController::class, 'getOne'])->name('admin.usertypes.get.one')->middleware(['auth']);
    Route::get('/delete', [UserTypeController::class, 'deleteOne'])->name('admin.usertypes.delete.one')->middleware(['auth']);
});

Route::prefix('/parkings')->group(function () {
    Route::get('/', [CarParkController::class, 'index'])->middleware(['auth', 'permitted']);
    Route::post('/enroll', [CarParkController::class, 'enroll'])->name('admin.parkings.enroll')->middleware(['auth']);
    Route::get('/slots/enroll', [CarParkController::class, 'enroll_slots'])->name('admin.parkingslot.enroll')->middleware(['auth']);
    Route::get('/slots/get-one', [CarParkController::class, 'getSlotOne'])->name('admin.parkings.slots.get.one')->middleware(['auth']);
    Route::get('/slots/get', [CarParkController::class, 'getAvailableSlots'])->name('admin.parkings.slots.get.all')->middleware(['auth']);
    Route::get('/slots/delete-one', [CarParkController::class, 'deleteSlotOne'])->name('admin.parkings.slots.delete.one')->middleware(['auth']);
    Route::get('/list', [CarParkController::class, 'list'])->name('admin.parkings.list')->middleware(['auth']);
    Route::get('/get', [CarParkController::class, 'getOne'])->name('admin.parkings.get.one')->middleware(['auth']);
    Route::get('/slots', [CarParkController::class, 'getSlots'])->name('admin.parkings.get.slots')->middleware(['auth']);
    Route::get('/delete', [CarParkController::class, 'deleteOne'])->name('admin.parkings.delete.one')->middleware(['auth']);
});

Route::prefix('/sale')->group(function () {
    Route::get('/', [SaleController::class, 'index'])->middleware(['auth', 'permitted']);
    Route::post('/enroll', [SaleController::class, 'enroll'])->name('admin.sale.enroll')->middleware(['auth']);
    Route::get('/list', [SaleController::class, 'list'])->name('admin.sale.list')->middleware(['auth']);
    Route::get('/report/list', [SaleController::class, 'listSalesReport'])->name('admin.sale.report.list')->middleware(['auth']);
    Route::get('/delete', [SaleController::class, 'deleteOne'])->name('admin.sale.delete')->middleware(['auth']);
    Route::get('/complete', [SaleController::class, 'makePayment'])->name('admin.sale.complete')->middleware(['auth']);
    Route::get('/emergency/on', [SaleController::class, 'checkingEmergency'])->name('admin.sale.emergency')->middleware(['auth']);
    Route::get('/emergency/off', [SaleController::class, 'offEmergency'])->name('admin.sale.emergency.off')->middleware(['auth']);
    Route::get('/scan/result', [SaleController::class, 'checkQRUser'])->name('admin.sale.scan.result')->middleware(['auth']);
});

Route::prefix('/sale-report')->group(function () {
    Route::get('/', [SaleController::class, 'indexSaleReport'])->middleware(['auth', 'permitted']);
    Route::get('/list', [SaleController::class, 'listSaleReport'])->name('admin.report.sale.list')->middleware(['auth']);
});

Route::prefix('/feedback-report')->group(function () {
    Route::get('/', [FeedbackController::class, 'index'])->middleware(['auth', 'permitted']);
    Route::get('/list', [FeedbackController::class, 'list'])->name('admin.report.feedback.list')->middleware(['auth']);
    Route::get('/get/one', [FeedbackController::class, 'getOne'])->name('admin.report.feedback.get.one')->middleware(['auth']);
});
