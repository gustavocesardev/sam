<?php

namespace App\Domain\Repository;

use App\Domain\Model\Abstract\PublicacaoReacaoAbstract;

interface ReacaoRepositoryInterface
{
    public function findByPublicacaoAndUsuario(int $idPublicacao, int $idUsuario): ?PublicacaoReacaoAbstract;
    public function savePublicacaoReacao(int $idPublicacao, int $idUsuario): void;
    public function findByUser(int $idUsuario): array;
}
