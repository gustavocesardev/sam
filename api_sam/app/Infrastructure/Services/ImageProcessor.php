<?php

namespace App\Infrastructure\Services;

use App\Application\Contracts\ImageProcessorInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageProcessor implements ImageProcessorInterface
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function storeUserProfileImage(UploadedFile $image, string $basePath): string
    {
        $filename = $this->gerarFilename($image);
        $path = $this->resolvePath($basePath, $filename);
        $content = $this->prepararImagemPerfil($image);

        Storage::disk('public')->put($path, $content);

        return $path;
    }

    public function storePublicacaoImages(array $imagens, string $basePath): array
    {
        return collect($imagens)
            ->filter(fn ($imagem) => $imagem instanceof UploadedFile)
            ->map(function (UploadedFile $imagem) use ($basePath) {
                $filename = $this->gerarFilename($imagem);
                $path = $this->resolvePath($basePath, $filename);
                $content = $this->prepararImagemPublicacao($imagem);

                Storage::disk('public')->put($path, $content);

                return $path;
            })
            ->values()
            ->all();
    }

    private function gerarFilename(UploadedFile $image): string
    {
        return uniqid() . '.' . $image->getClientOriginalExtension();
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

    private function resolvePath(string $basePath, string $filename): string
    {
        return trim($basePath, '/') . '/' . $filename;
    }
}