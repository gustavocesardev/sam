<?php

namespace App\Domain\Exceptions;

use Illuminate\Http\Response;

use Exception;

class LoginException extends AppException
{
    public function __construct(
        string $context,
        string $message = 'Erro ao efetuar o login.',
        int $code = Response::HTTP_UNAUTHORIZED,
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
