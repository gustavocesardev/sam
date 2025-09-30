<?php

namespace App\Domain\Repository\GrupoEstudo;

use App\Domain\Model\Abstract\PublicacaoReacaoAbstract;
use App\Domain\Repository\Contracts\ReacaoRepositoryContract;

interface ReacaoRepositoryInterface extends ReacaoRepositoryContract
{
    public function findByPublicacaoAndMembro(int $idPublicacao, int $idMembro): ?PublicacaoReacaoAbstract;
    public function savePublicacaoReacao(int $idPublicacao, int $idMembro): void;
    public function findByMembro(int $idUsuario): array;
}
