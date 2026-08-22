<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\NotificationController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get(
    '/email/verify/{id}/{hash}',
    [AuthController::class, 'verifyEmail']
)->name('verification.verify');


Route::middleware('auth:sanctum')->get('/email/status', function (Request $request) {
    return response()->json([
        'verified' => $request->user()->hasVerifiedEmail(),
    ]);
});

Route::middleware('auth:sanctum')->post(
    '/email/verification-notification',
    function (Request $request) {

        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email is already verified.',
            ], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification link sent.',
        ]);
    }
);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('products', ProductController::class)->except(['store', 'update', 'destroy']);
    
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::patch('/cart/{id}', [CartController::class, 'updateQuantity']);
    Route::delete('/cart/{id}', [CartController::class, 'delete']);
    Route::delete('/cart', [CartController::class, 'clear']);

    Route::post('/order/checkout', [OrderController::class, 'checkout']);
    Route::get('/order/list', [OrderController::class, 'list']);
    Route::get('/order/show/{id}', [OrderController::class, 'show']);
    Route::patch('/order/updateAdress/{id}/{address_id}', [OrderController::class, 'updateAdress']);
    Route::patch('/order/cancel-order/{id}', [OrderController::class, 'cancelOrder']);

    Route::get('/address/list', [AddressController::class, 'list']);
    Route::post('/address/add', [AddressController::class, 'add']);
    Route::post('/address/edit/{id}', [AddressController::class, 'edit']);
    Route::delete('/address/delete/{id}', [AddressController::class, 'delete']);

    Route::post('/payment/create-order-payment/{order_id}', [PaymentController::class, 'createPaymentOrder']);
    
    Route::get('/notifications', [NotificationController::class, 'index']);
});