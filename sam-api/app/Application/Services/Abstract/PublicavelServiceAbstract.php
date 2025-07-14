<?php

namespace App\Application\Services\Abstract;

use App\Application\Contracts\Infrastructure\CryptoServiceInterface;
use App\Application\Contracts\Infrastructure\ImageProcessorInterface;

use App\Domain\Model\Abstract\PublicacaoAbstract;
use App\Domain\Repository\Contracts\PublicacaoRepositoryContract;
use App\Domain\Exceptions\UnprocessableEntityException;
use App\Domain\Services\Abstract\KeywordServiceAbstract;
use App\Domain\VO\Auth\AuthenticatedUser;

abstract class PublicavelServiceAbstract
{
    public function __construct(
        protected string $errorContext,
        protected PublicacaoRepositoryContract $publicacaoRepository,
        protected KeywordServiceAbstract $keywordService,
        protected ImageProcessorInterface $imageProcessor,
        protected CryptoServiceInterface $cryptoService
    ) {}

    public function store(array $data): PublicacaoAbstract
    {
        if (!empty($data['id_publicacao_vinculada']))
        {
            $this->validarPublicacaoVinculada($data['id_publicacao_vinculada']);
        }

        $publicacao = $this->publicacaoRepository->store(data: $data);

        if (!empty($data['imagens']))
        {
            $this->registrarImagensPublicacao($publicacao, $data['imagens']);
        }

        $this->keywordService->publicacaoExtractAndStore($publicacao);

        return $publicacao->reload();
    }

    public function find(int $id): PublicacaoAbstract
    {
        $publicacao = $this->publicacaoRepository->find($id);
        return $publicacao;
    }

    public function delete(PublicacaoAbstract $publicacao, AuthenticatedUser $user): void
    {
        $this->imageProcessor->excluirDiretorio($publicacao->getBasePath());
        $publicacao->excluir();
    }

    private function validarPublicacaoVinculada(int $idPublicacaoVinculada): void
    {
        $publicacaoVinculada = $this->publicacaoRepository->find($idPublicacaoVinculada);

        if (!$publicacaoVinculada)
        {
            throw new UnprocessableEntityException(
                $this->errorContext,
                'A publicação vinculada informada é inválida. Verifique as informações.',
            );
        }
    }

    private function registrarImagensPublicacao(PublicacaoAbstract $publicacao, array $imagens): void
    {
        $basePath = $publicacao->getBasePath();
        
        $imagesPath = $this->imageProcessor->storeImages($imagens, $basePath);
        $hashPaths = collect($imagesPath)->map(fn ($path) => $this->cryptoService->encryptUrl($path))->values()->all();

        $publicacao->updateImagens($hashPaths);
        $this->publicacaoRepository->save($publicacao);
    }
}