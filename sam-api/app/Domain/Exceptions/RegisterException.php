<?php

namespace App\Domain\Exceptions;

use Illuminate\Http\Response;

use Exception;

class RegisterException extends AppException
{
    public function __construct(
        string $context,
        string $message = 'Erro ao efetuar o registro de usuário. Verifique as informações da Request.',
        int $code = Response::HTTP_UNPROCESSABLE_ENTITY,
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
