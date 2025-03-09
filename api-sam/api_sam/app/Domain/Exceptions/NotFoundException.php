<?php

namespace App\Domain\Exceptions;

use Illuminate\Http\Response;

use Exception;

class NotFoundException extends AppException
{
    public function __construct(
        string $context,
        int $code = Response::HTTP_NOT_FOUND,
        Exception $previous = null
    )
    {
        parent::__construct(
            "A entidade solicitada não existe ou não foi encontrada.",
            $context,
            $code,
            $previous
        );
    }
}
