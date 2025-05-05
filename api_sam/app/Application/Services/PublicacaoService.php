<?php

namespace App\Application\Services;

use App\Application\Contracts\CryptoServiceInterface;
use App\Application\Contracts\ImageProcessorInterface;

use App\Application\Services\Abstract\PublicavelServiceAbstract;
use App\Application\Services\KeywordService;

use App\Domain\Enums\ErrorContext;

use App\Domain\Repository\PublicacaoRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\VisualizacaoRepositoryInterface;
use App\Domain\Repository\ReacaoRepositoryInterface;

class PublicacaoService extends PublicavelServiceAbstract
{
    public function __construct(
        UserRepositoryInterface $userRepository,
        PublicacaoRepositoryInterface $publicacaoRepository,
        KeywordService $keywordService,
        VisualizacaoRepositoryInterface $visualizacaoRepository,
        ReacaoRepositoryInterface $reacaoRepository,
        ImageProcessorInterface $imageProcessor,
        CryptoServiceInterface $cryptoService
    ) {
        parent::__construct(
            ErrorContext::PUBLICACAO,
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
