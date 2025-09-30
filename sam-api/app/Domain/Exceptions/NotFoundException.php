<?php

namespace App\Domain\Exceptions;

use Illuminate\Http\Response;

use Exception;

class NotFoundException extends AppException
{
    public function __construct(
        string $context,
        string $message = 'A entidade solicitada não existe ou não foi encontrada.',
        int $code = Response::HTTP_NOT_FOUND,
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
