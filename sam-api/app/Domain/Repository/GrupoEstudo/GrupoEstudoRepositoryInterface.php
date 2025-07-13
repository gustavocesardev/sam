<?php

namespace App\Domain\Repository\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\GrupoEstudo;
use Illuminate\Support\Collection;

interface GrupoEstudoRepositoryInterface
{
    public function find(int $id): GrupoEstudo | null;
    public function store(array $data): GrupoEstudo;
    public function update(int $id, array $data): GrupoEstudo;
    public function delete(int $id): bool;
    public function searchByUsuarioCriador(int $idUsuario, int $limite = 15, int $page = 1): Collection;
    public function searchMostPopularNaoIngressadosByCurso(int $idUsuario, int $idCurso, int $limite = 15, int $page = 1): Collection;
}
