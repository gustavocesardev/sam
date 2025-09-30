<?php

namespace App\Domain\Repository;

use App\Domain\Model\ArtigoUniversitario;
use Illuminate\Support\Collection;

interface ArtigoUniversitarioRepositoryInterface
{
    public function find(int $id): ArtigoUniversitario;
    public function findByUsuario(int $idUsuario, int $limite = 15, int $page = 1): Collection;
    public function store(array $data): ArtigoUniversitario;
    public function update(int $id, array $data): ArtigoUniversitario;
    public function delete(int $id): bool;
    public function filtrarPorCampos(int $idInstituicao, array $filters, int $limite = 15, int $page = 1): Collection;
}