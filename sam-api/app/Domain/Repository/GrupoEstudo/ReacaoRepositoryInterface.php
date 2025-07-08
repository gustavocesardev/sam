<?php

namespace App\Domain\Repository\GrupoEstudo;

use App\Domain\Model\Abstract\PublicacaoReacaoAbstract;
use App\Domain\Repository\Abstract\ReacaoRepositoryAbstract;

interface ReacaoRepositoryInterface extends ReacaoRepositoryAbstract
{
    public function findByPublicacaoAndMembro(int $idPublicacao, int $idMembro): ?PublicacaoReacaoAbstract;
    public function savePublicacaoReacao(int $idPublicacao, int $idMembro): void;
    public function findByMembro(int $idUsuario): array;
}
