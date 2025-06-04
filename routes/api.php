<?php

use App\Http\Controllers\Api\ProcessController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\Api\StatusController;

Route::get('status/redis', [StatusController::class, 'redis']);
Route::get('status/database', [StatusController::class, 'database']);
Route::post('track', [TrackingController::class, 'track']);
Route::post('process', [ProcessController::class, 'process']);
