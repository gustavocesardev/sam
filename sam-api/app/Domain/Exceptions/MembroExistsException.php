<?php

namespace App\Domain\Exceptions;

use Illuminate\Http\Response;

use Exception;

class MembroExistsException extends AppException
{
    public function __construct(
        string $context,
        string $message = 'O usuário já está ingressado no grupo de estudo. Tenta ativar o registro.',
        int $code = Response::HTTP_UNPROCESSABLE_ENTITY,
        Exception $previous = null
    )
    {
        parent::__construct(
            $message,
            $context,
            $code,
            previous: $previous
        );
    }
}
