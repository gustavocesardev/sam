<?php

namespace App\Domain\Repository\Publicacao;

use App\Domain\Model\Abstract\PublicacaoReacaoAbstract;
use App\Domain\Repository\Abstract\ReacaoRepositoryAbstract;

interface ReacaoRepositoryInterface extends ReacaoRepositoryAbstract
{
    public function findByPublicacaoAndUsuario(int $idPublicacao, int $idUsuario): ?PublicacaoReacaoAbstract;
    public function savePublicacaoReacao(int $idPublicacao, int $idUsuario): void;
    public function findByUser(int $idUsuario): array;
    public function findByUserAndCurso(int $idUsuario, int $idCurso): array;
}
