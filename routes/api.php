<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AdminOrderController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('products', ProductController::class);
    
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

    Route::get('/admin/orders', [AdminOrderController::class, 'orders']);
    Route::patch('/admin/order-status/{order_id}/{status}', [AdminOrderController::class, 'updateOrderStatus']);
});