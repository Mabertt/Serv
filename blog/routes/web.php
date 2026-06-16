<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
use App\Http\Controllers\RestTestController;

use App\Http\Controllers\DiggingDeeperController;

Route::prefix('digging_deeper')->group(function () {
    Route::get('collections', [DiggingDeeperController::class, 'collections'])
        ->name('digging_deeper.collections');
});

Route::apiResource('rest', RestTestController::class)->names('restTest');