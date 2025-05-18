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

    private function armazenarImagem(UploadedFile $imagem, string $basePath): string
    {
        $filename = $this->gerarFilename($imagem);
        $path = $this->resolvePath($basePath, $filename);
        $conteudo = $this->prepararImagemPublicacao($imagem);

        Storage::disk('public')->put($path, $conteudo);

        return $path;
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