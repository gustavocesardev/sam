<?php

namespace App\Http\Controllers\Api\GrupoEstudo;

use App\Application\Services\GrupoEstudo\MembroService;
use App\Domain\Exceptions\AppException;
use App\Http\Controllers\Controller;

use App\Http\Requests\Store\GrupoEstudo\MembroRequest;
use App\Http\Resources\GrupoEstudo\MembroResource;
use App\Http\Utils\ApiResponse;

use Symfony\Component\HttpFoundation\Response;

class MembroController extends Controller
{
    public function __construct(private MembroService $membroService) {}

    public function show(int $id)
    {
        try {

            $membro = $this->membroService->find($id);
            return ApiResponse::success(
                new MembroResource($membro),
                'Detalhes de membro do grupo de estudo', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function store(MembroRequest $request)
    {
        try {

            $membro = $this->membroService->store($request->validated());
            return ApiResponse::success(
                new MembroResource($membro),
                'Membro adicionado com sucesso', 
                Response::HTTP_CREATED
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function ativar(string $id)
    {
        try {

            $this->membroService->ativarMembro($id);
            return ApiResponse::success(
                null, 
                'Membro ativado com sucesso', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function inativar(string $id)
    {
        try {

            $this->membroService->inativarMembro($id);
            return ApiResponse::success(
                null, 
                'Membro inativado com sucesso', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }
}