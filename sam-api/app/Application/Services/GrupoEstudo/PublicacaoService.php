<?php

namespace App\Application\Services\GrupoEstudo;

use App\Application\Contracts\Infrastructure\CryptoServiceInterface;
use App\Application\Contracts\Infrastructure\ImageProcessorInterface;

use App\Application\Services\Abstract\PublicavelServiceAbstract;
use App\Domain\Repository\GrupoEstudo\PublicacaoRepositoryInterface;
use App\Domain\Services\KeywordService;

use App\Domain\Enums\ErrorContext;

use App\Domain\Repository\GrupoEstudo\MembroRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\GrupoEstudo\VisualizacaoRepositoryInterface;
use App\Domain\Repository\GrupoEstudo\ReacaoRepositoryInterface;

class PublicacaoService extends PublicavelServiceAbstract
{
    public function __construct(
        private MembroRepositoryInterface $membroRepository,
        UserRepositoryInterface $userRepository,
        PublicacaoRepositoryInterface $publicacaoRepository,
        KeywordService $keywordService,
        VisualizacaoRepositoryInterface $visualizacaoRepository,
        ReacaoRepositoryInterface $reacaoRepository,
        ImageProcessorInterface $imageProcessor,
        CryptoServiceInterface $cryptoService
    ) {
        parent::__construct(
            ErrorContext::GRUPO_ESTUDO_PUBLICACAO,
            $userRepository,
            $publicacaoRepository,
            $keywordService,
            $visualizacaoRepository,
            $reacaoRepository,
            $imageProcessor,
            $cryptoService
        );
    }
}
