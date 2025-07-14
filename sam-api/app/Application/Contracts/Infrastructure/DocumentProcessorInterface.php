<?php

namespace App\Application\Contracts\Infrastructure;

use Illuminate\Http\UploadedFile;

interface DocumentProcessorInterface extends FileProcessorInterface
{
    public function storeDocument(UploadedFile $document, string $basePath): string;
    public function storeDocuments(array $documents, string $basePath): array;
}