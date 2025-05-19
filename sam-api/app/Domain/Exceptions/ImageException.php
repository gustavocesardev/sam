<?php

namespace App\Domain\Exceptions;

use Illuminate\Http\Response;

use Exception;

class ImageException extends AppException
{
    public function __construct(
        string $context,
        string $message = 'Hash da imagem inválido ou corrompido.',
        int $code = Response::HTTP_BAD_REQUEST,
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
