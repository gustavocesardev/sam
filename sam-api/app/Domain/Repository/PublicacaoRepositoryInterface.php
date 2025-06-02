<?php

namespace App\Domain\Repository;

use App\Domain\Model\Abstract\PublicacaoAbstract;
use Illuminate\Database\Eloquent\Collection;

interface PublicacaoRepositoryInterface
{
    public function find(int $id): PublicacaoAbstract;
    public function store(array $data): PublicacaoAbstract;
    public function update(int $id, array $data): PublicacaoAbstract;
    public function delete(int $id): bool;
    public function save(PublicacaoAbstract $publicacao): void;

    public function searchKeywords(array $keywords, int $limit = 10): Collection;
    public function searchKeywordsByCurso(int $idCurso, array $keywords, int $limit = 10): Collection;

    public function searchByIds(array $ids): Collection;
    public function searchWithReacaoAndVisualizacao(array $ids): Collection;
    
    public function searchMostPopularPublicacoes(array $excluirIds = [], int $limite = 10): Collection;
    public function searchMostPopularPublicacoesByCurso(int $idCurso, array $excluirIds = [], int $limite = 10): Collection;
}
