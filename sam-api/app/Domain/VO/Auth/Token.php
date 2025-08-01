<?php

namespace App\Domain\VO\Auth;

class Token
{
    private string $tokenType;
    private string $expiresIn;
    private string $accessToken;
    private string $refreshToken;

    public function __construct(private array $accessData)
    {
        $this->tokenType    = $accessData['token_type'];
        $this->expiresIn    = $accessData['expires_in'];
        $this->accessToken  = $accessData['access_token'];
        $this->refreshToken = $accessData['refresh_token'];
    }

    public function getAccesData(): array
    {
        return $this->accessData;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getExpiresIn(): string
    {
        return $this->expiresIn;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}

