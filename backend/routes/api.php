<?php

declare(strict_types=1);

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\V1\ArticleController;
use App\Http\Controllers\API\V1\AuthorController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\SourceController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::middleware('throttle:60,1')->get('/articles', [ArticleController::class, 'search']);
    Route::middleware('throttle:60,1')->get('/categories', [CategoryController::class, 'search']);
    Route::middleware('throttle:60,1')->get('/authors', [AuthorController::class, 'search']);
    Route::middleware('throttle:60,1')->get('/sources', [SourceController::class, 'search']);
});

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:api'])->get('/user', [AuthController::class, 'user']);
Route::middleware(['auth:api'])->put('/user', [AuthController::class, 'update']);
Route::middleware(['auth:api'])->get('/v1/user/articles', [ArticleController::class, 'getUserArticles']);
