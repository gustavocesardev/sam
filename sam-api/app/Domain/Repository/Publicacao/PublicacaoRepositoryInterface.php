<?php

namespace App\Domain\Repository\Publicacao;

use App\Domain\Repository\Abstract\PublicacaoRepositoryAbstract;
use Illuminate\Database\Eloquent\Collection;

interface PublicacaoRepositoryInterface extends PublicacaoRepositoryAbstract
{
    public function searchKeywordsByCurso(int $idCurso, array $keywords, int $limit = 10): Collection;
    public function searchMostPopularPublicacoesByCurso(int $idCurso, array $excluirIds = [], int $limite = 10): Collection;
}
