<?php

namespace App\Domain\Exceptions;

use Illuminate\Http\Response;

use Exception;

class AnoMaximoException extends AppException
{
    public function __construct(
        string $context,
        string $message = 'O ano máximo informado é inválido.',
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
