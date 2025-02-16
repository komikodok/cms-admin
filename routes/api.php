<?php

use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\RoomImageController;
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;


Route::apiResource('tenants', TenantController::class);
Route::apiResource('rooms', RoomController::class);
Route::apiResource('transactions', TransactionController::class);
Route::apiResource('payments', PaymentController::class);