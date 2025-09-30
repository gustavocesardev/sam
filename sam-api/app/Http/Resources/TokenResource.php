<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'token_type'    => $this->getTokenType(),
            'expires_in'    => $this->getExpiresIn(),
            'access_token'  => $this->getAccessToken(),
            'refresh_token' => $this->getRefreshToken(),
        ];
    }
}
