<?php

namespace App\Http\Controllers\Api;


use App\Application\Services\PublicacaoService;
use App\Domain\Exceptions\AppException;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\PublicacaoRequest;
use App\Http\Resources\PublicacaoResource;
use App\Http\Utils\ApiResponse;

use App\Infrastructure\Services\PaginatorService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicacaoController extends Controller
{
    public function __construct(private PublicacaoService $publicacaoService) {}

    public function store(PublicacaoRequest $request)
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

    public function show(string $id)
    {
        try {

            $publicacao = $this->publicacaoService->find($id);
            return ApiResponse::success(
                new PublicacaoResource($publicacao), 
                'Detalhes da publicação.', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function adicionarReacao(string $id)
    {
        try {

            $this->publicacaoService->adicionarReacao($id, auth()->user());
            return ApiResponse::success(
                null, 
                'Publicação curtida com sucesso.', 
                Response::HTTP_CREATED
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function removerReacao(string $id)
    {
        try {
            $this->publicacaoService->removerReacao($id, auth()->user());
            return ApiResponse::success(
                null, 
                'Curtida removida com sucesso.', 
                Response::HTTP_CREATED
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function destroy(string $id)
    {
        try {

            $publicacao = $this->publicacaoService->find($id);
            $this->publicacaoService->delete($publicacao, auth()->user());

            return ApiResponse::success(
                null, 
                'Publicação excluida com sucesso', 
                Response::HTTP_OK
            );

        } catch(AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function recomendar(Request $request)
    {
        try {

            $limite = $request->get('limite', 15);
            $page = $request->get('page', 1);

            $recomendadas = $this->publicacaoService->listFeedGeral(auth()->user(), $limite * $page);

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
}
