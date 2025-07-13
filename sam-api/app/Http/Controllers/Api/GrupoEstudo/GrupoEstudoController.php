<?php

namespace App\Http\Controllers\Api\GrupoEstudo;

use App\Application\Factories\AuthenticatedUserFactory;
use App\Application\Services\GrupoEstudo\GrupoEstudoService;

use App\Domain\Exceptions\AppException;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\GrupoEstudo\GrupoEstudoRequest;
use App\Http\Resources\GrupoEstudo\GrupoEstudoResource;
use App\Http\Utils\ApiResponse;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GrupoEstudoController extends Controller
{
    public function __construct(private GrupoEstudoService $grupoEstudoService) {}

    public function store(GrupoEstudoRequest $request): JsonResponse
    {
        try {

            $grupoEstudo = $this->grupoEstudoService->store($request->validated());
            return ApiResponse::success(
                new GrupoEstudoResource($grupoEstudo), 
                'Grupo de estudo criado com sucesso', 
                Response::HTTP_CREATED
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function update(GrupoEstudoRequest $request, string $id): JsonResponse
    {
        try {

            $grupoEstudo = $this->grupoEstudoService->update($id, $request->validated());
            return ApiResponse::success(
                new GrupoEstudoResource($grupoEstudo), 
                'Grupo de estudo atualizado com sucesso', 
                Response::HTTP_OK
            );
            
        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {

            $grupoEstudo = $this->grupoEstudoService->find($id);
            return ApiResponse::success(
                new GrupoEstudoResource($grupoEstudo), 
                'Detalhes do grupo de estudo.', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {

            $user = AuthenticatedUserFactory::fromAuth();
            $this->grupoEstudoService->delete($id, $user);
            
            return ApiResponse::success(
                null, 
                'Grupo de estudo excluido com sucesso', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function indexGruposUsuarioIngressado(Request $request): JsonResponse
    {
        try {
            
            $user = AuthenticatedUserFactory::fromAuth();

            $limite = $request->get('limit', default: 15);
            $page = $request->get('page', default: 1);

            $gruposEstudo = $this->grupoEstudoService->listarGruposIngressadosUsuario($user, $limite, $page);

            return ApiResponse::success(
                GrupoEstudoResource::collection($gruposEstudo), 
                'Grupos de estudo (Ingressados pelo usuário)', 
                Response::HTTP_OK
            );

        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function indexGruposUsuarioCriador(Request $request): JsonResponse
    {
        try {

            $user = AuthenticatedUserFactory::fromAuth();

            $limite = $request->get('limit', default: 15);
            $page = $request->get('page', default: 1);

            $gruposEstudo = $this->grupoEstudoService->listarGruposUsuarioCriador($user, $limite, $page);

            return ApiResponse::success(
                GrupoEstudoResource::collection($gruposEstudo), 
                'Grupos de estudo (Criados pelo usuário)', 
                Response::HTTP_OK
            );
            
        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function indexGruposPopularesNaoIngressados(Request $request): JsonResponse
    {
        try {

            $user = AuthenticatedUserFactory::fromAuth();

            $limite = $request->get('limit', default: 15);
            $page = $request->get('page', default: 1);

            $gruposEstudo = $this->grupoEstudoService->listarGruposPopularesNaoIngressados($user, $limite, $page);

            return ApiResponse::success(
                GrupoEstudoResource::collection($gruposEstudo), 
                'Grupos de estudo (Populares)', 
                Response::HTTP_OK
            );
            
        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }
}
