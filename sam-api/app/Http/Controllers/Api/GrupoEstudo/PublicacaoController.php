<?php

namespace App\Http\Controllers\Api\GrupoEstudo;

use App\Application\Factories\AuthenticatedUserFactory;
use App\Application\Services\GrupoEstudo\InteracoesService;
use App\Application\Services\GrupoEstudo\PublicacaoService;

use App\Domain\Exceptions\AppException;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\GrupoEstudo\PublicacaoRequest;
use App\Http\Resources\GrupoEstudo\PublicacaoResource;
use App\Http\Utils\ApiResponse;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PublicacaoController extends Controller
{
    public function __construct(
        private PublicacaoService $publicacaoService,
        private InteracoesService $interacoesService
    ) {}

    public function store(PublicacaoRequest $request): JsonResponse
    {
        try {

            $publicacao = $this->publicacaoService->store($request->validated());
            return ApiResponse::success(
                new PublicacaoResource($publicacao), 
                'Publicação criada com sucesso', 
                Response::HTTP_CREATED
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {

            $user = AuthenticatedUserFactory::fromAuth();
            $publicacao = $this->publicacaoService->find($id);

            $this->interacoesService->registrarVisualizacao($publicacao, $user);

            return ApiResponse::success(
                new PublicacaoResource($publicacao), 
                'Detalhes da publicação.', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function adicionarReacao(string $id): JsonResponse
    {
        try {

            $user = AuthenticatedUserFactory::fromAuth();
            $this->interacoesService->adicionarReacao($id, $user);
            
            return ApiResponse::success(
                null, 
                'Publicação curtida com sucesso.', 
                Response::HTTP_CREATED
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function removerReacao(string $id): JsonResponse
    {
        try {

            $user = AuthenticatedUserFactory::fromAuth();
            $this->interacoesService->removerReacao($id, $user);

            return ApiResponse::success(
                null, 
                'Curtida removida com sucesso.', 
                Response::HTTP_CREATED
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {

            $user = AuthenticatedUserFactory::fromAuth();
            $publicacao = $this->publicacaoService->find($id);

            $this->publicacaoService->delete($publicacao, $user);

            return ApiResponse::success(
                null, 
                'Publicação excluida com sucesso', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }
}
