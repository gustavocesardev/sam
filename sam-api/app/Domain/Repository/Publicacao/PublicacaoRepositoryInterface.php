<?php

namespace App\Domain\Repository\Publicacao;

use App\Domain\Repository\Contracts\PublicacaoRepositoryContract;
use Illuminate\Support\Collection;

interface PublicacaoRepositoryInterface extends PublicacaoRepositoryContract
{
    public function searchKeywordsByCurso(int $idCurso, array $keywords, int $limit = 10): Collection;
    public function searchMostPopularPublicacoesByCurso(int $idCurso, array $excluirIds = [], int $limite = 10): Collection;
    public function searchByUsuario(int $idUsuario, int $limite = 15, int $page = 1): Collection;
}
