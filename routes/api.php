<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('perfil', [AuthController::class, 'perfil']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::apiResource('proyectos', ProyectoController::class);
Route::get('uf', [ProyectoController::class, 'mostrarUF']);

Route::get('ping', fn () => response()->json(['pong' => true]));

Route::get('/proyectos', [ProyectoController::class, 'index']);
    Route::post('/proyectos', [ProyectoController::class, 'store']);
    Route::get('/proyectos/{id}', [ProyectoController::class, 'show']);
    Route::put('/proyectos/{id}', [ProyectoController::class, 'update']);
    Route::delete('/proyectos/{id}', [ProyectoController::class, 'destroy']);
