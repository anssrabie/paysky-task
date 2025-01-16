<?php

use App\Http\Controllers\Api\V1\User\Auth\LoginController;
use Illuminate\Support\Facades\Route;


Route::post('/login', LoginController::class)->middleware('throttle:5,1');

