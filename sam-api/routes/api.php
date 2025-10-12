<?php

use App\Http\Controllers\Api\ArtigoUniversitarioController;
use App\Http\Controllers\Api\FormularioController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileController;

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
    Route::get('/instituicao/{id}', [CursoController::class, 'cursoByInstituicao'])->name('curso.cursoByInstituicao');
    Route::post('/', [CursoController::class, 'store'])->name('curso.store');
    Route::get('{id}', [CursoController::class, 'show'])->name('curso.show'); 
    Route::put('{id}', [CursoController::class, 'update'])->name('curso.update');
    Route::delete('{id}', [CursoController::class, 'destroy'])->name('curso.destroy');
});

// Rotas referentes à imagens
Route::prefix('file')->group(function () {
    Route::get('image/{hash}', [FileController::class, 'show']);
    Route::get('document/{hash}', [FileController::class, 'show']);
});

// Rotas referentes ao usuário
Route::middleware('auth:api')->prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('{id}', [UserController::class, 'show']);
    Route::get('{id}/details', [UserController::class, 'showDetails']);
    Route::put('{id}', [UserController::class, 'update']);
    Route::delete('{id}', [UserController::class, 'destroy']);
});

// Rotas referentes à publicação
Route::middleware('auth:api')->prefix('publicacao')->group(function () {
    Route::post('/', [PublicacaoController::class, 'store']);
    Route::get('{id}', [PublicacaoController::class, 'show']);

    Route::post('/{id}/reacao', [PublicacaoController::class, 'adicionarReacao']);
    Route::delete('/{id}/reacao', [PublicacaoController::class, 'removerReacao']);

    Route::delete('{id}', [PublicacaoController::class, 'destroy']);

    Route::get('{id}/vinculadas', [PublicacaoController::class, 'listPublicacoesVinculadas']);
});

// Rotas referente aos feeds
Route::middleware('auth:api')->prefix('feed')->group(function () {
    Route::get('/', [PublicacaoController::class, 'recomendar']);
    Route::get('/curso', [PublicacaoController::class, 'recomendarCurso']);
    Route::get('/grupo-estudo/{id}', [GrupoEstudoPublicacaoController::class, 'recomendar']);

    Route::get('/usuario/{idUsuario}', [PublicacaoController::class, 'listPublicacoesUsuario']);
    Route::get('/usuario/{idUsuario}/curtidas', [PublicacaoController::class, 'listPublicacoesCurtidasUsuario']);
});

// Rotas referentes ao grupo de estudo
Route::middleware('auth:api')->prefix('grupo-estudo')->group(function () {
    
    Route::post('/', [GrupoEstudoController::class, 'store']);
    Route::put('{id}', [GrupoEstudoController::class, 'update']);
    Route::get('{id}', [GrupoEstudoController::class, 'show']);
    Route::delete('{id}', [GrupoEstudoController::class, 'destroy']);

    Route::get('/{id}/membros', [MembroController::class, 'listarMembroPorGrupo']);

    Route::get('/grupos/ingressados', [GrupoEstudoController::class, 'indexGruposUsuarioIngressado']);
    Route::get('/grupos/criador', [GrupoEstudoController::class, 'indexGruposUsuarioCriador']);
    Route::get('/grupos/populares', [GrupoEstudoController::class, 'indexGruposPopularesNaoIngressados']);
    
    Route::get('/{idGrupoEstudo}/publicacao/{idPublicacao}', [GrupoEstudoPublicacaoController::class, 'show']);
    Route::get('/{idGrupoEstudo}/publicacao/{idPublicacao}/vinculadas', [GrupoEstudoPublicacaoController::class, 'listPublicacoesVinculadas']);
});

Route::middleware('auth:api')->prefix('grupo-estudo/membro')->group(function () {
    
    Route::post('/', [MembroController::class, 'store']);
    Route::get('{id}', [MembroController::class, 'show']);
});

Route::middleware('auth:api')->prefix('grupo-estudo/publicacao')->group(function () {
    
    Route::post('/', [GrupoEstudoPublicacaoController::class, 'store']);
    Route::get('{id}', [GrupoEstudoPublicacaoController::class, 'show']);
    
    Route::post('/{id}/reacao', [GrupoEstudoPublicacaoController::class, 'adicionarReacao']);
    Route::delete('/{id}/reacao', [GrupoEstudoPublicacaoController::class, 'removerReacao']);
    
    Route::delete('{id}', [GrupoEstudoPublicacaoController::class, 'destroy']);
});

// Rotas referentes aos formulários
Route::middleware('auth:api')->prefix('formulario')->group(function () {

    Route::post('/', [FormularioController::class, 'store']);
    Route::post('/filtrar', [FormularioController::class, 'filtrarPorCampos']);
    Route::get('/criados', [FormularioController::class, 'formulariosUsuario']);

    Route::get('{id}', [FormularioController::class, 'show']);
    Route::put('{id}', [FormularioController::class, 'update']);
    Route::delete('{id}', [FormularioController::class, 'destroy']);

});

// Rotas referentes aos artigos universitários
Route::middleware('auth:api')->prefix('artigo-universitario')->group(function () {

    Route::post('/', [ArtigoUniversitarioController::class, 'store']);
    Route::post('/filtrar', [ArtigoUniversitarioController::class, 'filtrarPorCampos']);
    Route::get('/criados', [ArtigoUniversitarioController::class, 'artigosUsuario']);

    Route::get('{id}', [ArtigoUniversitarioController::class, 'show']);
    Route::put('{id}', [ArtigoUniversitarioController::class, 'update']);
    Route::delete('{id}', [ArtigoUniversitarioController::class, 'destroy']);
});