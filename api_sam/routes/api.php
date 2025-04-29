<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\InstituicaoController;
use App\Http\Controllers\Api\PublicacaoController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Rotas de login e verificação de e-mail
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

// Rotas referente à instituição
Route::prefix('instituicao')->group(function () {
    Route::get('/', [InstituicaoController::class, 'index'])->name('instituicao.index');
    Route::post('/', [InstituicaoController::class, 'store'])->name('instituicao.store');
    Route::get('{id}', [InstituicaoController::class, 'show'])->name('instituicao.show'); 
    Route::put('{id}', [InstituicaoController::class, 'update'])->name('instituicao.update');
    Route::delete('{id}', [InstituicaoController::class, 'destroy'])->name('instituicao.destroy');
});

// Rotas referentes ao curso
Route::prefix('curso')->group(function () {
    Route::get('/', [CursoController::class, 'index'])->name('curso.index');
    Route::post('/', [CursoController::class, 'store'])->name('curso.store');
    Route::get('{id}', [CursoController::class, 'show'])->name('curso.show'); 
    Route::put('{id}', [CursoController::class, 'update'])->name('curso.update');
    Route::delete('{id}', [CursoController::class, 'destroy'])->name('curso.destroy');
});

// Rotas referentes ao usuário
Route::middleware('auth:api')->prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('{id}', [UserController::class, 'show']);
    Route::put('{id}', [UserController::class, 'update']);
    Route::delete('{id}', [UserController::class, 'destroy']);
});

// Rotas referentes à publicação
Route::middleware('auth:api')->prefix('publicacao')->group(function () {

    Route::post('/', [PublicacaoController::class, 'store']);
    Route::get('{id}', [PublicacaoController::class, 'show']);
    Route::put('{id}', [PublicacaoController::class, 'update']);
    Route::delete('{id}', [PublicacaoController::class, 'destroy']);
});