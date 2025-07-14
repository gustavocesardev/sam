<?php

namespace App\Infrastructure\Services;

use App\Application\Contracts\Infrastructure\ImageProcessorInterface;

use App\Infrastructure\Services\Abstract\FileProcessorService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageProcessorService extends FileProcessorService implements ImageProcessorInterface
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function storeImage(UploadedFile $image, string $basePath): string
    {
        $filename = $this->gerarFilename($image);
        $path = $this->resolvePath($basePath, $filename);
        $content = $this->prepararImagemPerfil($image);

        Storage::disk('public')->put($path, $content);

        return $path;
    }

    public function storeImages(array $imagens, string $basePath): array
    {
        return collect($imagens)
            ->filter(fn ($imagem) => $imagem instanceof UploadedFile)
            ->map(fn (UploadedFile $imagem) => $this->armazenarImagem($imagem, $basePath))
            ->values()
            ->all();
    }

    private function armazenarImagem(UploadedFile $imagem, string $basePath): string
    {
        $filename = $this->gerarFilename($imagem);
        $path = $this->resolvePath($basePath, $filename);
        $conteudo = $this->prepararImagemPublicacao($imagem);

        Storage::disk('public')->put($path, $conteudo);

        return $path;
    }

    private function prepararImagemPerfil(UploadedFile $image): string
    {
        $img = $this->manager->read($image);
        $img->scale(width: 300);
        return (string) $img->toJpeg(90);
    }

    private function prepararImagemPublicacao(UploadedFile $image): string
    {
        $img = $this->manager->read($image);

        if ($image->getSize() > 2 * 1024 * 1024)
        {
            $img->scale(width: 1200);
        }

        return (string) $img->toJpeg(80);
    }
}