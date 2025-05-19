<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\InstituicaoService;
use App\Domain\Exceptions\AppException;

use App\Http\Resources\InstituicaoResource;
use App\Http\Utils\ApiResponse;
use App\Http\Requests\Store\InstituicaoRequest;
use App\Http\Controllers\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class InstituicaoController extends Controller
{
    public function __construct(private InstituicaoService $instituicaoService) {}

    public function index(): JsonResponse
    {
        try {

            $instituicoes = $this->instituicaoService->listAll();
            return ApiResponse::success(
                InstituicaoResource::collection($instituicoes), 
                'Listagem de instituições', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function store(InstituicaoRequest $request): JsonResponse
    {
        try {

            $instituicao = $this->instituicaoService->store($request->validated());
            return ApiResponse::success(
                new InstituicaoResource($instituicao), 
                'Instituição criada com sucesso', 
                Response::HTTP_CREATED
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {

            $instituicao = $this->instituicaoService->find($id);
            return ApiResponse::success(
                new InstituicaoResource($instituicao), 
                'Detalhes da instituição', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function update(InstituicaoRequest $request, string $id): JsonResponse
    {
        try {

            $instituicao = $this->instituicaoService->update($id, $request->validated());
            return ApiResponse::success(
                new InstituicaoResource($instituicao), 
                'Instituição atualizada com sucesso', 
                Response::HTTP_OK
            );
            
        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {

            $this->instituicaoService->delete($id);
            return ApiResponse::success(
                null, 
                'Instituição excluida com sucesso', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }
}
