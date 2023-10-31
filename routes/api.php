<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\CurrencyController;
use App\Http\Controllers\API\V1\DepositController;
use App\Http\Controllers\API\V1\PaymentController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    Route::group([
        'prefix' => 'auth'

    ], function () {

        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    });


    Route::group([
        'middleware' => 'auth',
        'prefix' => 'payments'

    ], function () {
        Route::post('/', [PaymentController::class, 'store']);
        Route::get('/', [PaymentController::class, 'index']);
        Route::get('/{payment}', [PaymentController::class, 'show']);
        Route::patch('/{payment}/reject', [PaymentController::class, 'reject']);
        Route::patch('/{payment}/approve', [PaymentController::class, 'approve']);
        Route::delete('/{payment}', [PaymentController::class, 'destroy']);
    });

    Route::group([
        'middleware' => 'auth',
        'prefix' => 'currencies'

    ], function () {
        Route::get('/', [CurrencyController::class, 'index']);
        Route::post('/', [CurrencyController::class, 'store']);
        Route::patch('/{currency}/active', [CurrencyController::class, 'active']);
        Route::patch('/{currency}/deactive', [CurrencyController::class, 'deActive']);
    });







    Route::post('deposits/transfer', [DepositController::class, 'transfer']);
});
