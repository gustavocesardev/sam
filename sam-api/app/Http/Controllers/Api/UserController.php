<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\UserService;
use App\Domain\Exceptions\AppException;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Utils\ApiResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function index(): JsonResponse
    {
        try {

            $users = $this->userService->listAll();
            return ApiResponse::success(
                UserResource::collection($users),
                'Listagem de usuários.',
                Response::HTTP_OK
            );

        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function store(UserRequest $request): JsonResponse
    {
        try {

            $user = $this->userService->store($request->validated());
            return ApiResponse::success(
                new UserResource($user),
                'Usuário criado com sucesso.',
                Response::HTTP_CREATED
            );

        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {

            $user = $this->userService->find($id);
            return ApiResponse::success(
                new UserResource($user),
                'Detalhes do usuário.',
                Response::HTTP_OK
            );

        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function update(UserRequest $request, string $id): JsonResponse
    {
        try {

            $user = $this->userService->update($id, $request->validated());
            return ApiResponse::success(
                new UserResource($user),
                'Usuário atualizado com sucesso.',
                Response::HTTP_OK
            );

        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {

            $this->userService->delete($id);
            return ApiResponse::success(
                null,
                'Usuário excluído com sucesso.',
                Response::HTTP_OK
            );
            
        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }
}
