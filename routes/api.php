<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('tasks/{id}', [UserController::class, 'index']);#->middleware('auth:sanctum');
Route::get('tasks', [UserController::class, 'Tasks']);#->middleware('auth:sanctum');
