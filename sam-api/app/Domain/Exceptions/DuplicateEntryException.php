<?php

namespace App\Domain\Exceptions;

use Illuminate\Http\Response;

use Exception;

class DuplicateEntryException extends AppException
{
    public function __construct(
        string $attribute,
        string $context,
        int $code = Response::HTTP_CONFLICT,
        Exception $previous = null
    )
    {
        parent::__construct(
            "O atributo único '{$attribute}' já está em uso.",
            $context,
            $code,
            $previous
        );
    }
}
