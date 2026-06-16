<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Blog\PostController;

Route::group(['prefix' => 'blog'], function () {
    Route::apiResource('posts', PostController::class)->names('blog.posts');
});

use App\Http\Controllers\Api\Blog\Admin\CategoryController as AdminCategoryController;

// Група маршрутів для адмін-панелі блогу
Route::prefix('admin/blog')->group(function () {
    Route::apiResource('categories', AdminCategoryController::class)
        ->only(['index', 'store', 'update'])
        ->names('blog.admin.categories');
});