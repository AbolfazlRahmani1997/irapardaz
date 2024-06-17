<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LinkController;


Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::post('/links', [LinkController::class, 'create']);
Route::get('/links/top', [LinkController::class, 'topLinks']);
Route::get('/users/{userId}/links', [LinkController::class, 'userLinks']);
Route::get('/links/search', [LinkController::class, 'search']);
Route::get('/r/{shortenedUrl}', [LinkController::class, 'redirect']);
