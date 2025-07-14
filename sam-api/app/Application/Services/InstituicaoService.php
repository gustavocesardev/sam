<?php

namespace App\Application\Services;

use App\Application\Contracts\Infrastructure\CryptoServiceInterface;
use App\Application\Contracts\Infrastructure\ImageProcessorInterface;

use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\DuplicateEntryException;
use App\Domain\Model\Instituicao;
use App\Domain\Repository\InstituicaoRepositoryInterface;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class InstituicaoService
{
    public function __construct(
        private InstituicaoRepositoryInterface $instituicaoRepository,
        private ImageProcessorInterface $imageProcessor,
        private CryptoServiceInterface $cryptoService
    ) {}

    public function listAll(): Collection
    {
        return $this->instituicaoRepository->findAll();
    }
    
    public function find(int $id): Instituicao
    {
        return $this->instituicaoRepository->find($id);
    }

    public function store(array $data): Instituicao
    {
        $this->validarDuplicidade(null, $data);
        $instituicao = $this->instituicaoRepository->store($data);

        if (array_key_exists('imagem', $data))
        {
            if ($data['imagem'] instanceof UploadedFile)
            {
                $this->atualizarImagem($instituicao, $data['imagem']);
                return $instituicao->reload();
            } 

            $this->removerImagem($instituicao);
            return $instituicao->reload();
        }

        return $instituicao->reload();
    }

    public function update(int $id, array $data): Instituicao
    {
        $this->validarDuplicidade($id, $data);        
        $instituicao = $this->instituicaoRepository->update($id, $data);

        if (array_key_exists('imagem', $data))
        {
            if ($data['imagem'] instanceof UploadedFile)
            {
                $this->atualizarImagem($instituicao, $data['imagem']);
                return $instituicao->reload();
            } 

            $this->removerImagem($instituicao);
            return $instituicao->reload();
        }

        return $instituicao->reload();
    }

    private function atualizarImagem(Instituicao $instituicao, UploadedFile $imagem): void
    {
        $path = $this->imageProcessor->storeImage($imagem, $instituicao->getBasePath());
        $hashPath = $this->cryptoService->encryptUrl($path);

        $instituicao->updateImagem($hashPath);
    }

    private function removerImagem(Instituicao $instituicao): void
    {
        if (!empty($instituicao->imagem))
        {
            $this->imageProcessor->excluirArquivo($instituicao->imagem);
            $instituicao->updateImagem();
        }
    }

    public function delete(int $id): void
    {
        $instituicao = $this->find($id);
        $this->imageProcessor->excluirDiretorio($instituicao->getBasePath());
        
        $this->instituicaoRepository->delete($id);
    }

    private function validarDuplicidade(?int $id = null, array $data): void
    {
        $instituicao = $this->instituicaoRepository->findByDominio($data['dominio_email_institucional']);

        if ($instituicao && $instituicao->id != $id)
        {
            throw new DuplicateEntryException(
                'dominio_email_institucional',
                ErrorContext::INSTITUICAO
            );
        }
    }
}
