<?php

namespace App\Http\Controllers\Api\GrupoEstudo;

use App\Application\Services\GrupoEstudo\GrupoEstudoService;
use App\Domain\Exceptions\AppException;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\GrupoEstudo\GrupoEstudoRequest;
use App\Http\Resources\GrupoEstudo\GrupoEstudoResource;
use App\Http\Utils\ApiResponse;

use Symfony\Component\HttpFoundation\Response;

class GrupoEstudoController extends Controller
{
    public function __construct(private GrupoEstudoService $grupoEstudoService) {}

    public function store(GrupoEstudoRequest $request)
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

    public function update(GrupoEstudoRequest $request, string $id)
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

    public function show(string $id)
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

    public function destroy(string $id)
    {
        try {

            $this->grupoEstudoService->delete($id, auth()->user());
            return ApiResponse::success(
                null, 
                'Grupo de estudo excluido com sucesso', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }
}
