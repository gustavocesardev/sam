<?php

namespace App\Application\Services;

use App\Application\Contracts\ImageProcessorInterface;

use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\UnprocessableEntityException;

use App\Domain\Model\Publicacao;
use App\Domain\Model\User;

use App\Domain\Repository\PublicacaoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\VisualizacaoRepositoryInterface;

class PublicacaoService
{
    public function __construct(
        private PublicacaoRepositoryInterface $publicacaoRepository,
        private UserRepositoryInterface $userRepository,
        private ImageProcessorInterface $imageProcessor,
        private KeywordService $keywordService,
        private VisualizacaoRepositoryInterface $visualizacaoRepository
    ) {}

    // TODO: Não permitir publicação que não seja do usuário logado
    public function store(array $data): Publicacao
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

    public function find(int $id): Publicacao
    {
        $publicacao = $this->publicacaoRepository->find($id);
        $this->registrarVisualizacao($publicacao, auth()->user());

        return $publicacao;
    }

    private function registrarVisualizacao(Publicacao $publicacao, ?User $user)
    {
        if (!empty($user) && !empty($publicacao))
        {  
            $this->visualizacaoRepository->store($publicacao->id, $user->id);
        }
    }

    private function validarPublicacaoVinculada(int $idPublicacaoVinculada)
    {
        $publicacaoVinculada = $this->publicacaoRepository->find($idPublicacaoVinculada);

        if (empty($publicacaoVinculada))
        {
            throw new UnprocessableEntityException(
                ErrorContext::PUBLICACAO,
                'A publicação vinculada informada é inválida. Verifique as informações.',
            );
        }
    }

    private function registrarImagensPublicacao(Publicacao $publicacao, array $imagens)
    {
        // TODO: Tratar exceptions de imagens
        $user = $this->userRepository->findWithCurso($publicacao->id_usuario);
        
        $idInstituicao = $user->curso->id_instituicao;
        $idCurso = $user->curso->id;
        $idUsuario = $user->id;

        $basePath  = "instituicoes/{$idInstituicao}/cursos/{$idCurso}/users/{$idUsuario}/publicacoes/{$publicacao->id}";

        $imagesPath = $this->imageProcessor->storePublicacaoImages($imagens, $basePath );

        $publicacao->updateImagens($imagesPath);
        $this->publicacaoRepository->save($publicacao);
    }
}