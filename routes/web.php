<?php

use App\Http\Controllers\ConfirmTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::patch('transactions/confirm/{id}', [ConfirmTransactionController::class, 'confirm']);
