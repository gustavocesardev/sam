<?php

namespace App\Domain\Exceptions;

use Illuminate\Http\Response;

use Exception;

class UnprocessableEntityException extends AppException
{
    public function __construct(
        string $context,
        string $message = 'Não foi possível processar a entidade solicitada. Verifique as informações da request.',
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
