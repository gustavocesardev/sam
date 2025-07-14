<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\FormularioService;
use App\Domain\Exceptions\AppException;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\FormularioRequest;
use App\Http\Resources\FormularioResource;
use App\Http\Utils\ApiResponse;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FormularioController extends Controller
{
    public function __construct(private FormularioService $formularioService) {}

    public function store(FormularioRequest $request): JsonResponse
    {
        try {

            $formulario = $this->formularioService->store($request->validated());
            return ApiResponse::success(
                new FormularioResource($formulario), 
                'Formulário criado com sucesso.', 
                Response::HTTP_CREATED
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {

            $formulario = $this->formularioService->find($id);
            return ApiResponse::success(
                new FormularioResource($formulario), 
                'Detalhes do formulário.', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function update(FormularioRequest $request, string $id): JsonResponse
    {
        try {

            $formulario = $this->formularioService->update($id, $request->validated());
            return ApiResponse::success(
                new FormularioResource($formulario), 
                'Formulário atualizado com sucesso.', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {

            $this->formularioService->delete($id);
            return ApiResponse::success(
                null, 
                'Formulário excluido com sucesso.', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }
}
