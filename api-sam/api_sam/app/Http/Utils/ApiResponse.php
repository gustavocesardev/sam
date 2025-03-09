<?php

namespace App\Http\Utils;

use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\AppException;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(
        $data,
        $message = 'Operação realizada com sucesso',
        $code = 200
    ): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    public static function error(AppException $exception): JsonResponse
    {
        return response()->json([
            'erro' => true,
            'description' => ErrorContext::getDescription($exception->getContext()),
            'message' => $exception->getMessage(),
            'context' => $exception->getContext(),
            'code' => $exception->getCode(),
        ], $exception->getCode());
    }
}
