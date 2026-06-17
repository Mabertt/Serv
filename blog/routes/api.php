<?php

use App\Http\Controllers\Api\Blog\Admin\CategoryController;
use App\Http\Controllers\Api\Blog\Admin\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

$groupData = [
    'prefix' => 'admin/blog',
];

Route::group($groupData, function () {
    Route::apiResource('categories', CategoryController::class)
        ->names('blog.admin.categories');

    Route::apiResource('posts', PostController::class)
        ->except(['show'])
        ->names('blog.admin.posts');
});