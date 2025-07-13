<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ImageController;

use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\InstituicaoController;
use App\Http\Controllers\Api\UserController;

use App\Http\Controllers\Api\PublicacaoController as PublicacaoController;

use App\Http\Controllers\Api\GrupoEstudo\GrupoEstudoController;
use App\Http\Controllers\Api\GrupoEstudo\MembroController;
use App\Http\Controllers\Api\GrupoEstudo\PublicacaoController as GrupoEstudoPublicacaoController;


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

// Rotas referentes à imagens
Route::prefix('image')->group(function () {
    Route::get('/{hash}', [ImageController::class, 'show']);
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

    Route::post('/reacao/{id}', [PublicacaoController::class, 'adicionarReacao']);
    Route::delete('/reacao/{id}', [PublicacaoController::class, 'removerReacao']);

    Route::delete('{id}', [PublicacaoController::class, 'destroy']);
});

// Rotas referente aos feeds
Route::middleware('auth:api')->prefix('feed')->group(function () {
    Route::get('/', [PublicacaoController::class, 'recomendar']);
    Route::get('/curso', [PublicacaoController::class, 'recomendarCurso']);
    Route::get('/grupo-estudo/{id}', [GrupoEstudoPublicacaoController::class, 'recomendar']);
});

// Rotas referentes ao grupo de estudo
Route::middleware('auth:api')->prefix('grupo-estudo')->group(function () {
    
    Route::post('/', [GrupoEstudoController::class, 'store']);
    Route::put('{id}', [GrupoEstudoController::class, 'update']);
    Route::get('{id}', [GrupoEstudoController::class, 'show']);
    Route::delete('{id}', [GrupoEstudoController::class, 'destroy']);
});

Route::middleware('auth:api')->prefix('grupo-estudo/membro')->group(function () {
    
    Route::post('/', [MembroController::class, 'store']);
    Route::get('{id}', [MembroController::class, 'show']);
});

Route::middleware('auth:api')->prefix('grupo-estudo/publicacao')->group(function () {
    
    Route::post('/', [GrupoEstudoPublicacaoController::class, 'store']);
    Route::get('{id}', [GrupoEstudoPublicacaoController::class, 'show']);

    Route::post('/reacao/{id}', [GrupoEstudoPublicacaoController::class, 'adicionarReacao']);
    Route::delete('/reacao/{id}', [GrupoEstudoPublicacaoController::class, 'removerReacao']);
    
    Route::delete('{id}', [GrupoEstudoPublicacaoController::class, 'destroy']);
});