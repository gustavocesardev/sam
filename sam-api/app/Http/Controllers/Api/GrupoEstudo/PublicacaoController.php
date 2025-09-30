<?php

namespace App\Http\Controllers\Api\GrupoEstudo;

use App\Application\Factories\AuthenticatedUserFactory;
use App\Application\Services\GrupoEstudo\GrupoEstudoService;
use App\Application\Services\GrupoEstudo\InteracoesService;
use App\Application\Services\GrupoEstudo\MembroService;
use App\Application\Services\GrupoEstudo\PublicacaoService;

use App\Domain\Exceptions\AppException;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\GrupoEstudo\PublicacaoRequest;
use App\Http\Resources\GrupoEstudo\PublicacaoResource;
use App\Http\Utils\ApiResponse;

use App\Infrastructure\Services\PaginatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicacaoController extends Controller
{
    public function __construct(
        private PublicacaoService $publicacaoService,
        private GrupoEstudoService $grupoEstudoService,
        private MembroService $membroService,
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

    public function show(int $idGrupoEstudo, int $idPublicacao): JsonResponse
    {
        try {

            $user = AuthenticatedUserFactory::fromAuth();

            $publicacao = $this->publicacaoService->find($idPublicacao);
            $membro = $this->membroService->findByUsuarioAndGrupo($user, $idGrupoEstudo);
            $this->interacoesService->registrarVisualizacao($publicacao, $user);

            $publicacao = $this->publicacaoService->marcarCurtida($publicacao, $membro->id);

            return ApiResponse::success(
                new PublicacaoResource(resource: $publicacao), 
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

        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function recomendar(string $id, Request $request): JsonResponse 
    {
        try {

            $user = AuthenticatedUserFactory::fromAuth();

            $limite = $request->get('limite', default: 15);
            $page = $request->get('page', default: 1);

            $grupoEstudo = $this->grupoEstudoService->find($id);
            $recomendadas = $this->publicacaoService->listFeedGeral($user, $grupoEstudo, $limite * $page);
            $paginated = PaginatorService::paginateCollection($recomendadas, $limite, $page);

            return ApiResponse::success(
                PublicacaoResource::collection($paginated), 
                'Publicacações do Grupo de estudo', 
                Response::HTTP_OK
            );

        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function listPublicacoesVinculadas(Request $request, int $idGrupoEstudo, int $idPublicacao): JsonResponse
    {
        try {

            $user = AuthenticatedUserFactory::fromAuth();

            $limite = $request->get('limite', 15);
            $page = $request->get('page', 1);

            $grupoEstudo = $this->grupoEstudoService->find($idGrupoEstudo);
            $publicacoes = $this->publicacaoService->listPublicacoesVinculadas($user, $idPublicacao, $grupoEstudo, $limite, $page);

            return ApiResponse::success(
                PublicacaoResource::collection($publicacoes),
                'Publicações do grupo de estudo vinculadas',
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }
}
