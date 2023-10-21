<?php

use App\Http\Controllers\API\V1\CurrencyController;
use App\Http\Controllers\API\V1\PaymentController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::post('payments', [PaymentController::class, 'store']);
    Route::get('payments', [PaymentController::class, 'index']);
    Route::get('payments/{payment}', [PaymentController::class, 'show']);
    Route::patch('payments/{payment}/reject', [PaymentController::class, 'reject']);
    Route::patch('payments/{payment}/approve', [PaymentController::class, 'approve']);

    Route::post('currencies', [CurrencyController::class, 'store']);
});
