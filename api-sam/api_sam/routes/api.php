<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\InstituicaoController;
use Illuminate\Support\Facades\Route;

// Rotas de login e verificação de e-mail
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

Route::prefix('instituicao')->group(function () {
    Route::get('/', [InstituicaoController::class, 'index'])->name('instituicao.index');
    Route::post('/', [InstituicaoController::class, 'store'])->name('instituicao.store');
    Route::get('{id}', [InstituicaoController::class, 'show'])->name('instituicao.show'); 
    Route::put('{id}', [InstituicaoController::class, 'update'])->name('instituicao.update');
    Route::delete('{id}', [InstituicaoController::class, 'destroy'])->name('instituicao.destroy');
});

Route::prefix('curso')->group(function () {
    Route::get('/', [CursoController::class, 'index'])->name('curso.index');
    Route::post('/', [CursoController::class, 'store'])->name('curso.store');
    Route::get('{id}', [CursoController::class, 'show'])->name('curso.show'); 
    Route::put('{id}', [CursoController::class, 'update'])->name('curso.update');
    Route::delete('{id}', [CursoController::class, 'destroy'])->name('curso.destroy');
});