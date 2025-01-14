<?php

use App\Http\Controllers\Ai\V1\User\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('products',ProductController::class)->only('index','show');
