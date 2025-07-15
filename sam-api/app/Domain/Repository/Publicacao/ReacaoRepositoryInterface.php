<?php

namespace App\Domain\Repository\Publicacao;

use App\Domain\Model\Abstract\PublicacaoReacaoAbstract;
use App\Domain\Repository\Contracts\ReacaoRepositoryContract;
use Illuminate\Support\Collection;

interface ReacaoRepositoryInterface extends ReacaoRepositoryContract
{
    public function findByPublicacaoAndUsuario(int $idPublicacao, int $idUsuario): PublicacaoReacaoAbstract | null;
    public function savePublicacaoReacao(int $idPublicacao, int $idUsuario): void;
    public function findByUser(int $idUsuario): array;
    public function findByUserAndCurso(int $idUsuario, int $idCurso): array;
    public function searchByUsuario(int $idUsuario, int $limite = 15, int $page = 1): Collection;
}
