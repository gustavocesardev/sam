<?php

namespace App\Application\Contracts\Infrastructure;

use Illuminate\Http\UploadedFile;

interface ImageProcessorInterface extends FileProcessorInterface
{
    public function storeImage(UploadedFile $image, string $basePath): string;
    public function storeImages(array $imagens, string $basePath): array;
}