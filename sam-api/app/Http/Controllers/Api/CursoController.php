<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\CursoService;
use App\Domain\Exceptions\AppException;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\CursoRequest;
use App\Http\Resources\CursoResource;
use App\Http\Utils\ApiResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class CursoController extends Controller
{
    public function __construct(private CursoService $cursoService) {}

    public function index(): JsonResponse
    {
        try {

            $cursos = $this->cursoService->listAll();
            return ApiResponse::success(
                CursoResource::collection($cursos), 
                'Listagem de cursos ativos.', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function store(CursoRequest $request): JsonResponse
    {
        try {

            $curso = $this->cursoService->store($request->validated());
            return ApiResponse::success(
                new CursoResource($curso), 
                'Curso criado com sucesso.', 
                Response::HTTP_CREATED
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {

            $curso = $this->cursoService->find($id);
            return ApiResponse::success(
                new CursoResource($curso), 
                'Detalhes do curso.', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function update(CursoRequest $request, string $id)
    {
        try {

            $curso = $this->cursoService->update($id, $request->validated());
            return ApiResponse::success(
                new CursoResource($curso), 
                'Curso atualizado com sucesso.', 
                Response::HTTP_OK
            );
            
        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {

            $this->cursoService->delete($id);
            return ApiResponse::success(
                null, 
                'Curso excluido com sucesso.', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }
}