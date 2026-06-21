<?php

use App\Http\Controllers\Api\Blog\Admin\CategoryController;
use App\Http\Controllers\Api\Blog\Admin\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiggingDeeperController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

$groupData = [
    'prefix' => 'admin/blog',
];

Route::group($groupData, function () {
    // Маршрути для категорій
    Route::apiResource('categories', CategoryController::class)
        ->names('blog.admin.categories');

    // Маршрути для постів
    Route::apiResource('posts', PostController::class)
        ->names('blog.admin.posts');

    // Інші маршрути
    Route::get('process-video', [DiggingDeeperController::class, 'processVideo'])
        ->name('digging_deeper.processVideo');

    Route::get('prepare-catalog', [DiggingDeeperController::class, 'prepareCatalog'])
        ->name('digging_deeper.prepareCatalog');
});