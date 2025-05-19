<?php

namespace App\Domain\Exceptions;

use Illuminate\Http\Response;

use Exception;

class EmailException extends AppException
{
    public function __construct(
        string $context,
        string $message = 'Erro ao processar E-mail.',
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
