<?php

namespace App\Application\Services\Abstract;

use App\Application\Contracts\CryptoServiceInterface;
use App\Application\Contracts\ImageProcessorInterface;
use App\Application\Services\KeywordService;

use App\Domain\Model\Abstract\PublicacaoAbstract;
use App\Domain\Model\User;
use App\Domain\Repository\PublicacaoRepositoryInterface;
use App\Domain\Repository\ReacaoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\VisualizacaoRepositoryInterface;
use App\Domain\Exceptions\UnprocessableEntityException;

abstract class PublicavelServiceAbstract
{
    public function __construct(
        protected string $errorContext,
        protected UserRepositoryInterface $userRepository,
        protected PublicacaoRepositoryInterface $publicacaoRepository,
        protected KeywordService $keywordService,
        protected VisualizacaoRepositoryInterface $visualizacaoRepository,
        protected ReacaoRepositoryInterface $reacaoRepository,
        protected ImageProcessorInterface $imageProcessor,
        protected CryptoServiceInterface $cryptoService
    ) {}

    // TODO: Não permitir publicação que não seja do usuário logado
    public function store(array $data): PublicacaoAbstract
    {
        if (!empty($data['id_publicacao_vinculada']))
        {
            $this->validarPublicacaoVinculada($data['id_publicacao_vinculada']);
        }

        $publicacao = $this->publicacaoRepository->store($data);

        if (!empty($data['imagens']))
        {
            $this->registrarImagensPublicacao($publicacao, $data['imagens']);
        }

        $this->keywordService->publicacaoExtractAndStore($publicacao);

        return $publicacao->atualizar();
    }

    public function find(int $id): PublicacaoAbstract
    {
        $publicacao = $this->publicacaoRepository->find($id);
        $this->registrarVisualizacao($publicacao, auth()->user());

        return $publicacao;
    }

    protected function registrarVisualizacao(PublicacaoAbstract $publicacao, User $user)
    {
        $this->visualizacaoRepository->store($publicacao->id, $user->id);
        $publicacao->adicionarVisualizacao();
    }

    private function validarPublicacaoVinculada(int $idPublicacaoVinculada)
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

    private function registrarImagensPublicacao(PublicacaoAbstract $publicacao, array $imagens)
    {
        // TODO: Tratar/Disparar exceptions de imagens
        $basePath = $publicacao->getBasePath();

        $imagesPath = $this->imageProcessor->storeImages($imagens, $basePath);
        $hashPaths = collect($imagesPath)->map(fn ($path) => $this->cryptoService->encryptUrl($path))->values()->all();

        $publicacao->updateImagens($hashPaths);
        $this->publicacaoRepository->save($publicacao);
    }

    public function adicionarReacao(int $idPublicacao, User $user)
    {
        $publicacao = $this->find($idPublicacao);
        $publicacaoReacao = $this->reacaoRepository->findByPublicacaoAndUsuario($publicacao->id, $user->id);

        if ($publicacaoReacao)
        {
            if ($publicacaoReacao->situacao == 'I')
            {
                $publicacaoReacao->ativar();
                $publicacao->adicionarReacao();
            }

            return;
        }

        $this->reacaoRepository->savePublicacaoReacao($publicacao->id, $user->id);
        $publicacao->adicionarReacao();
    }

    public function removerReacao(int $idPublicacao, User $user)
    {
        $publicacao = $this->find($idPublicacao);
        $publicacaoReacao = $this->reacaoRepository->findByPublicacaoAndUsuario($publicacao->id, $user->id);

        if ($publicacaoReacao)
        {
            if ($publicacaoReacao->situacao == 'A')
            {   
                $publicacaoReacao->inativar();
                $publicacao->removerReacao();
            }
        }
    }

    // TODO: Verificar se a publicacao é do usuário logado antes de excluir
    public function delete(PublicacaoAbstract $publicacao, User $user): void
    {
        $this->imageProcessor->excluirDiretorio($publicacao->getBasePath());
        $publicacao->excluir();
    }
}