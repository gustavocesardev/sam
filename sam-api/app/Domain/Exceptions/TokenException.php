<?php

namespace App\Domain\Exceptions;

use Illuminate\Http\Response;

use Exception;

class TokenException extends AppException
{
    public function __construct(
        string $context,
        string $message = 'Erro ao gerar o token/refresh token.',
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        Exception $previous = null
    )
    {
        parent::__construct(
            $message,
            $context,
            $code,
            $previous
        );
    }
}
