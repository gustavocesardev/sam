<?php

namespace App\Domain\Repository;

use App\Domain\Model\Abstract\AbstractPublicacaoReacao;

interface ReacaoRepositoryInterface
{
    public function findByPublicacaoAndUsuario(int $idPublicacao, int $idUsuario): ?AbstractPublicacaoReacao;
    public function savePublicacaoReacao(int $idPublicacao, int $idUsuario): void;
}
