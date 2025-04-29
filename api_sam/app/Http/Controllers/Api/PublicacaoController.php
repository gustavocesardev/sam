<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\PublicacaoService;
use App\Domain\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\PublicacaoRequest;
use App\Http\Resources\PublicacaoResource;
use App\Http\Utils\ApiResponse;
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
