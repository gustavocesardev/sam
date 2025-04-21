<?php

namespace App\Application\Contracts;

use Illuminate\Http\UploadedFile;

interface ImageProcessorInterface
{
    public function storeUserProfileImage(UploadedFile $image, string $basePath): string;
}