<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\AuthService;
use App\Domain\Exceptions\AppException;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Utils\ApiResponse;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        try {

            $this->authService->register($request->validated());
            return ApiResponse::success(
                null, 
                'Verifique seu e-mail para ativar a conta', 
                Response::HTTP_CREATED
            );

        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }    
    }

    public function verify(Request $request): View
    {
        $data = [
            'id' => $request->route('id'),
            'hash' => $request->route('hash'),
        ];

        try {

            $this->authService->verifyEmail($data);

            return view('emails.verify', [
                'message' => 'E-mail verificado com sucesso!',
            ]);

        } catch (AppException $exception) {
            return view('emails.verify', [
                'message' => $exception->getMessage(),
            ]);
        }      
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {

            $tokenData = $this->authService->login($request->validated());
            return ApiResponse::success(
                $tokenData, 
                'Login efetuado com sucesso.', 
            );

        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }

    public function refreshToken(Request $request): JsonResponse
    {
        try {

            $validated = $request->validate(['refresh_token' => 'required']);
            $tokenData = $this->authService->refreshToken($validated['refresh_token']);
            return ApiResponse::success(
                $tokenData,
                'Refresh do token efetuado com sucesso.'
            );
            
        } catch (AppException $exception) {
            return ApiResponse::error($exception);
        }
    }
}