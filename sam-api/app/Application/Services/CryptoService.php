<?php

namespace App\Application\Services;

use App\Application\Contracts\CryptoServiceInterface;
use Illuminate\Support\Facades\Crypt;

class CryptoService implements CryptoServiceInterface
{
    public function encryptUrl(string $path): string
    {
        return urlencode(Crypt::encryptString($path));
    }

    public function decryptUrl(string $encrypted): string
    {
        return Crypt::decryptString(urldecode($encrypted));
    }
}
