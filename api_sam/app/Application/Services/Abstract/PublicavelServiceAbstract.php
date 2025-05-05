<?php

namespace App\Application\Services\Abstract;

use App\Application\Contracts\CryptoServiceInterface;
use App\Application\Contracts\ImageProcessorInterface;
use App\Application\Services\KeywordService;

use App\Domain\Model\Abstract\AbstractPublicacao;
use App\Domain\Model\User;

use App\Domain\Repository\PublicacaoRepositoryInterface;
use App\Domain\Repository\ReacaoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\VisualizacaoRepositoryInterface;

use App\Domain\Exceptions\UnprocessableEntityException;

abstract class PublicavelServiceAbstract
{
    public function __construct(
        private string $errorContext,
        private UserRepositoryInterface $userRepository,
        private PublicacaoRepositoryInterface $publicacaoRepository,
        private KeywordService $keywordService,
        private VisualizacaoRepositoryInterface $visualizacaoRepository,
        private ReacaoRepositoryInterface $reacaoRepository,
        private ImageProcessorInterface $imageProcessor,
        private CryptoServiceInterface $cryptoService
    ) {}

    // TODO: Não permitir publicação que não seja do usuário logado
    public function store(array $data): AbstractPublicacao
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

        return $publicacao->refresh();
    }

    public function find(int $id): AbstractPublicacao
    {
        $publicacao = $this->publicacaoRepository->find($id);
        $this->registrarVisualizacao($publicacao, auth()->user());

        return $publicacao;
    }

    private function registrarVisualizacao(AbstractPublicacao $publicacao, User $user)
    {
        $this->visualizacaoRepository->store($publicacao->id, $user->id);
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

    private function registrarImagensPublicacao(AbstractPublicacao $publicacao, array $imagens)
    {
        // TODO: Tratar/Disparar exceptions de imagens
        $user = $this->userRepository->findWithCurso($publicacao->getIdUsuario());
        $basePath = $publicacao->getBaseImagePath($user);

        $imagesPath = $this->imageProcessor->storePublicacaoImages($imagens, $basePath);
        $hashPaths = collect($imagesPath)->map(fn ($path) => $this->cryptoService->encryptUrl($path))->values()->all();

        $publicacao->updateImagens($hashPaths);
        $this->publicacaoRepository->save($publicacao);
    }

    public function adicionarReacao(AbstractPublicacao $publicacao, User $user)
    {
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

    public function removerReacao(AbstractPublicacao $publicacao, User $user)
    {
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

    public function delete(AbstractPublicacao $publicacao, User $user): void
    {
        $this->imageProcessor->excluirDiretorio($publicacao->getBaseImagePath($user));
        $publicacao->excluir();
    }
}