<?php

namespace App\Domain\Exceptions;

use Exception;

class AppException extends Exception
{
    protected $context;

    public function __construct(
        string $message,
        string $context = '',
        int $code = 0,
        Exception $previous = null
    )
    {
        $this->context = $context;
        parent::__construct($message, $code, $previous);
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function toArray(): array
    {
        return [
            'error'   => true,
            'message' => $this->getMessage(),
            'context' => $this->getContext(),
            'code'    => $this->getCode(),
        ];
    }
}
