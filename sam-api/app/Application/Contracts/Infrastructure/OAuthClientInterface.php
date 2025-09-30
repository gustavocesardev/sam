<?php

namespace App\Application\Contracts\Infrastructure;

interface OAuthClientInterface
{
    public function getAccessToken(string $email, string $password): array;
    public function refreshToken(string $refreshToken): array;
}
