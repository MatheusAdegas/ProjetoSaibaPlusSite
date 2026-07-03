<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\AuthController;

// Categorias
Route::get('/categorias', [CategoriaController::class, 'index']);

// Cursos
Route::get('/cursos',       [CursoController::class, 'index']); // ?categoria_id=1
Route::get('/cursos/{id}',  [CursoController::class, 'show']);

// Autenticação
Route::post('/usuarios',    [AuthController::class, 'register']);
Route::post('/auth/login',  [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);