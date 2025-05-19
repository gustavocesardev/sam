<?php

namespace App\Application\Contracts;

interface CryptoServiceInterface
{
    public function encryptUrl(string $path): string;
    public function decryptUrl(string $encrypted): string;
}
