<?php

namespace App\Infrastructure\Auth;

use App\Application\Contracts\Infrastructure\OAuthClientInterface;

use App\Domain\Exceptions\TokenException;
use App\Domain\Enums\ErrorContext;

use Illuminate\Support\Facades\Http;

class OAuthPassportClient implements OAuthClientInterface
{
    public function getAccessToken(string $email, string $password): array
    {
        $response = Http::asForm()->post(config('app.url').'/oauth/token', [
            'grant_type'    => 'password',
            'client_id'     => env('PASSPORT_PASSWORD_GRANT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),
            'username'      => $email,
            'password'      => $password,
            'scope'         => '',
        ]);

        if ($response->failed())
        {
            $hint = !empty($response['hint']) ? $response['hint'] : '';

            throw new TokenException(
                ErrorContext::AUTH_ACCESS_TOKEN,
                 "{$response['error_description']} {$hint}",
                $response->status()
            );
        }

        return $response->json();
    }

    public function refreshToken(string $refreshToken): array
    {
        $response = Http::asForm()->post(url('/oauth/token'), [
            'grant_type'    => 'refresh_token',
            'client_id'     => env('PASSPORT_PASSWORD_GRANT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),
            'refresh_token' => $refreshToken,
            'scope'         => '',
        ]);

        if ($response->failed())
        {
            $hint = !empty($response['hint']) ? $response['hint'] : '';

            throw new TokenException(
                ErrorContext::AUTH_REFRESH_TOKEN,
                 "{$response['error_description']} {$hint}",
                $response->status()
            );
        }

        return $response->json();
    }
}
