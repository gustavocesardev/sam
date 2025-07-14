<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\ArtigoUniversitarioService;
use App\Domain\Exceptions\AppException;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\ArtigoUniversitarioRequest;
use App\Http\Resources\ArtigoUniversitarioResource;
use App\Http\Utils\ApiResponse;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ArtigoUniversitarioController extends Controller
{
    public function __construct(private ArtigoUniversitarioService $artigoUniversitarioService) {}

    public function store(ArtigoUniversitarioRequest $request): JsonResponse
    {
        try {

            $artigoUniversitario = $this->artigoUniversitarioService->store($request->validated());
            return ApiResponse::success(
                new ArtigoUniversitarioResource($artigoUniversitario), 
                'Artigo universitário criado com sucesso.', 
                Response::HTTP_CREATED
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {

            $artigoUniversitario = $this->artigoUniversitarioService->find($id);
            return ApiResponse::success(
                new ArtigoUniversitarioResource($artigoUniversitario), 
                'Detalhes artigo universitário.', 
                Response::HTTP_CREATED
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function update(ArtigoUniversitarioRequest $request, string $id)
    {
        try {

            $artigoUniversitario = $this->artigoUniversitarioService->update($id, $request->validated());
            return ApiResponse::success(
                new ArtigoUniversitarioResource($artigoUniversitario), 
                'Artigo universitário atualizado com sucesso.', 
                Response::HTTP_CREATED
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {

            $this->artigoUniversitarioService->delete($id);
            return ApiResponse::success(
                null, 
                'Artigo universitário excluido com sucesso.', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }
}