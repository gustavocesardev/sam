<?php

namespace App\Domain\VO;

use InvalidArgumentException;

class Email
{
    private string $email;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            throw new InvalidArgumentException('E-mail inválido.');
        }

        $this->email = strtolower($email);
    }

    public function get(): string
    {
        return $this->email;
    }

    public function getHash(): string
    {
        return sha1($this->email);
    }

    public function getDominio(): string
    {
        return substr(strrchr($this->email, "@"), 1);
    }
}
