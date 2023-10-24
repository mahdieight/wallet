<?php

use App\Http\Controllers\API\V1\CurrencyController;
use App\Http\Controllers\API\V1\DepositController;
use App\Http\Controllers\API\V1\PaymentController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    Route::prefix('payments')->group(function () {
        Route::post('/', [PaymentController::class, 'store']);
        Route::get('/', [PaymentController::class, 'index']);
        Route::get('/{payment}', [PaymentController::class, 'show']);
        Route::patch('/{payment}/reject', [PaymentController::class, 'reject']);
        Route::patch('/{payment}/approve', [PaymentController::class, 'approve']);
    });

    Route::prefix('currencies')->group(function () {
        Route::get('/', [CurrencyController::class, 'index']);
        Route::post('/', [CurrencyController::class, 'store']);
        Route::patch('/{currency}/active', [CurrencyController::class, 'active']);
        Route::patch('/{currency}/deactive', [CurrencyController::class, 'deActive']);
    });




    Route::post('deposit/transfer', [DepositController::class, 'transfer']);
});
