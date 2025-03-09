<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\AuthService;
use App\Domain\Exceptions\AppException;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Utils\ApiResponse;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(UserRequest $request)
    {
        $this->authService->register($request->validated());

        return ApiResponse::success(
            null, 
            'Verifique seu e-mail para ativar a conta', 
            Response::HTTP_CREATED
        );
    }

    public function verify(Request $request)
    {
        $data = [
            'id' => $request->route('id'),
            'hash' => $request->route('hash'),
        ];

        $status = $this->authService->verifyEmail($data); 
        $httpCode = $status->success ? Response::HTTP_OK : Response::HTTP_FORBIDDEN;
  
        return ApiResponse::success(
            null, 
            $status->message, 
            $httpCode
        );
    }

    public function login(LoginRequest $request)
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

    public function refreshToken(Request $request)
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
