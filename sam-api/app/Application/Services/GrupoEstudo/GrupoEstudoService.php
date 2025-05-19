<?php

namespace App\Application\Services\GrupoEstudo;

use App\Application\Contracts\CryptoServiceInterface;
use App\Application\Contracts\ImageProcessorInterface;

use App\Domain\Model\GrupoEstudo\GrupoEstudo;
use App\Domain\Model\User;
use App\Domain\Repository\GrupoEstudo\GrupoEstudoRepositoryInterface;

use Illuminate\Http\UploadedFile;

class GrupoEstudoService
{
    public function __construct(
        private GrupoEstudoRepositoryInterface $grupoEstudoRepository,
        private ImageProcessorInterface $imageProcessor,
        private CryptoServiceInterface $cryptoService
    ) {}
    
    public function find(int $id): GrupoEstudo
    {
        return $this->grupoEstudoRepository->find($id);
    }

    public function store(array $data): GrupoEstudo
    {
        $grupoEstudo = $this->grupoEstudoRepository->store($data);

        $this->atualizarImagem($grupoEstudo, $data['imagem']);
        $this->atualizarImagemHeader($grupoEstudo, $data['imagem_header']);

        return $grupoEstudo->atualizar();
    }

    public function update(int $id, array $data): GrupoEstudo
    {
        $grupoEstudo = $this->grupoEstudoRepository->update($id, $data);

        $this->atualizarImagem($grupoEstudo, $data['imagem']);
        $this->atualizarImagemHeader($grupoEstudo, $data['imagem_header']);

        return $grupoEstudo->atualizar();
    }

    public function atualizarImagem(GrupoEstudo $grupoEstudo, UploadedFile $imagem)
    {
        if (!empty($grupoEstudo->imagem))
        {
            $this->imageProcessor->excluirArquivo($grupoEstudo->imagem);
        }

        $path = $this->imageProcessor->storeImage($imagem, $grupoEstudo->getImagePath());
        $hashPath = $this->cryptoService->encryptUrl($path);

        $grupoEstudo->updateImagem($hashPath);
    }

    public function atualizarImagemHeader(GrupoEstudo $grupoEstudo, UploadedFile $imagem)
    {
        if (!empty($grupoEstudo->imagem_header))
        {
            $this->imageProcessor->excluirArquivo($grupoEstudo->imagem_header);
        }

        $path = $this->imageProcessor->storeImage($imagem, $grupoEstudo->getImageHeaderPath());
        $hashPath = $this->cryptoService->encryptUrl($path);

        $grupoEstudo->updateImagemHeader($hashPath);
    }

    public function delete(int $id, User $usuario)
    {
        $grupoEstudo = $this->find(id: $id);

        if ($grupoEstudo->id_usuario == $usuario->id)
        {
            $this->imageProcessor->excluirDiretorio($grupoEstudo->getBasePath());
            $grupoEstudo->excluir();
        }
    }
}