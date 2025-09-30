<?php

namespace App\Domain\Repository\Contracts;

use App\Domain\Model\Abstract\PublicacaoAbstract;
use Illuminate\Support\Collection;

interface PublicacaoRepositoryContract
{
    public function find(int $id): PublicacaoAbstract;
    public function store(array $data): PublicacaoAbstract;
    public function update(int $id, array $data): PublicacaoAbstract;
    public function delete(int $id): bool;
    public function save(PublicacaoAbstract $publicacao): void;

    public function searchKeywords(int $idReferencia, array $keywords, int $limit = 10): Collection;
    public function searchByIds(array $ids): Collection;
    public function searchMostPopularPublicacoes(int $idReferencia, array $excluirIds = [], int $limite = 10): Collection;
    public function searchWithReacaoAndVisualizacao(array $ids): Collection;
    public function searchVinculadas(int $idPublicacao, int $limite = 10, int $page = 1): Collection;
    public function hasReacao(int $idReferencia, int $idPublicacao): bool;
}