<?php

namespace App\Infrastructure\Services;

use App\Application\Contracts\Infrastructure\DocumentProcessorInterface;
use App\Infrastructure\Services\Abstract\FileProcessorService;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentProcessorService extends FileProcessorService implements DocumentProcessorInterface
{
    public function storeDocument(UploadedFile $document, string $basePath): string
    {
        $filename = $this->gerarFilename($document);
        $path = $this->resolvePath($basePath, $filename);

        Storage::disk('public')->putFileAs($basePath, $document, $filename);

        return $path;
    }

    public function storeDocuments(array $documents, string $basePath): array
    {
        return collect($documents)
            ->filter(fn ($document) => $document instanceof UploadedFile)
            ->map(fn (UploadedFile $document) => $this->storeDocument($document, $basePath))
            ->values()
            ->all();
    }
}