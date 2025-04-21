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
        $content = $this->redimensionarImagem($image, 300);

        Storage::disk('public')->put($path, $content);

        return $path;
    }

    private function gerarFilename(UploadedFile $image): string
    {
        return uniqid() . '.' . $image->getClientOriginalExtension();
    }

    private function redimensionarImagem(UploadedFile $image, int $width): string
    {
        $img = $this->manager->read($image);
        $img->scale(width: $width);

        return (string) $img->toJpeg();
    }

    private function resolvePath(string $basePath, string $filename): string
    {
        return trim($basePath, '/') . '/' . $filename;
    }
}
