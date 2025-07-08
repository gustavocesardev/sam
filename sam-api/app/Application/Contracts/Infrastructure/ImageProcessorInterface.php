<?php

namespace App\Application\Contracts\Infrastructure;

use Illuminate\Http\UploadedFile;

interface ImageProcessorInterface
{
    public function storeImage(UploadedFile $image, string $basePath): string;
    public function storeImages(array $imagens, string $basePath): array;
    public function excluirArquivo(string $path): void;
    public function excluirDiretorio(string $path): void;
}