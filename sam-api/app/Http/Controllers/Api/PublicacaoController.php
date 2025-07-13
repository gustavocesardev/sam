<?php

namespace App\Http\Controllers\Api;

use App\Application\Factories\AuthenticatedUserFactory;
use App\Application\Services\Publicacao\InteracoesService;
use App\Application\Services\Publicacao\PublicacaoService;

use App\Domain\Exceptions\AppException;

use App\Infrastructure\Services\PaginatorService;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\PublicacaoRequest;
use App\Http\Resources\PublicacaoResource;
use App\Http\Utils\ApiResponse;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function recomendar(Request $request): JsonResponse
    {
        try {

            $user = AuthenticatedUserFactory::fromAuth();

            $limite = $request->get('limite', 15);
            $page = $request->get('page', 1);

            $recomendadas = $this->publicacaoService->listFeedGeral($user, $limite * $page);
            $paginated = PaginatorService::paginateCollection($recomendadas, $limite, $page);

            return ApiResponse::success(
                PublicacaoResource::collection($paginated), 
                'Publicacações (Feed principal)', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function recomendarCurso(Request $request): JsonResponse
    {
        try {

            $user = AuthenticatedUserFactory::fromAuth();

            $limite = $request->get('limite', 15);
            $page = $request->get('page', 1);

            $recomendadas = $this->publicacaoService->listFeedCurso($user, $limite * $page);
            $paginated = PaginatorService::paginateCollection($recomendadas, $limite, $page);

            return ApiResponse::success(
                PublicacaoResource::collection($paginated), 
                'Publicacações (Feed Curso)', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }
}
