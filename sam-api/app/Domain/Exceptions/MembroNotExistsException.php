<?php

namespace App\Domain\Exceptions;

use Illuminate\Http\Response;

use Exception;

class MembroNotExistsException extends AppException
{
    public function __construct(
        string $context,
        string $message = 'Não é possível concluir o processo pois o usuário não é membro do grupo vinculado a publicação.',
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
