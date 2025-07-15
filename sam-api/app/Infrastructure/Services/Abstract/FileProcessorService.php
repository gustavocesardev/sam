<?php

namespace App\Infrastructure\Services\Abstract;

use App\Application\Contracts\Infrastructure\FileProcessorInterface;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

abstract class FileProcessorService implements FileProcessorInterface
{
    public function excluirArquivo(string $path): void
    {
        Storage::disk('public')->delete($path);
    }

    public function excluirDiretorio(string $path): void
    {
        if (Storage::disk('public')->exists($path))
        {
            $arquivos = Storage::disk('public')->files($path);
            foreach ($arquivos as $arquivo) {
                Storage::disk('public')->delete($arquivo);
            }

            $subDiretorios = Storage::disk('public')->directories($path);
            foreach ($subDiretorios as $subDiretorio) {
                $this->excluirDiretorio($subDiretorio); // Chama recursivamente
            }
 
            Storage::disk('public')->deleteDirectory($path);
        }
    }

    public function gerarFilename(UploadedFile $file): string
    {
        return uniqid() . '.' . $file->getClientOriginalExtension();
    }

    public function resolvePath(string $basePath, string $filename): string
    {
        return trim($basePath, '/') . '/' . $filename;
    }
}