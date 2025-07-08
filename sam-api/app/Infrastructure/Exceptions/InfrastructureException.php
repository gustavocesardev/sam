<?php

namespace App\Infrastructure\Exceptions;

use Exception;

class InfrastructureException extends Exception
{
    protected string $context;

    public function __construct(
        string $message,
        string $context = 'infrastructure',
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
