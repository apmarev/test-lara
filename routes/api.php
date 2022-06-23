<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AmoController;

Route::get('/amo/auth', [AmoController::class, 'amoKey']);
Route::get('/amo/get', [AmoController::class, 'getAmo']);
