<?php

namespace App\Application\Contracts\Infrastructure;

use Illuminate\Http\UploadedFile;

interface FileProcessorInterface
{
    public function excluirArquivo(string $path): void;
    public function excluirDiretorio(string $path): void;
    public function gerarFilename(UploadedFile $file): string;
    public function resolvePath(string $basePath, string $filename): string;
}