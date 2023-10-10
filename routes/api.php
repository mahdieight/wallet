<?php

use App\Http\Controllers\API\V1\PaymentController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::post('payments', [PaymentController::class, 'store']);
    Route::patch('payments/{payment}/reject', [PaymentController::class, 'reject']);
    Route::get('payments', [PaymentController::class, 'index']);
});
