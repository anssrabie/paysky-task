<?php

use App\Http\Controllers\Ai\V1\User\OrderController;
use App\Http\Controllers\Ai\V1\User\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products',ProductController::class)->only('index','show');
Route::post('orders',[OrderController::class,'store']);
Route::patch('orders/payment-status',[OrderController::class,'updatePaymentStatus']);
