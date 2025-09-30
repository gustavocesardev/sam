<?php

namespace App\Domain\Repository\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\Membro;
use App\Domain\Model\User;

use Illuminate\Support\Collection;

interface MembroRepositoryInterface
{
    public function find(int $id): Membro | null;
    public function findByUsuarioAndGrupo(int $idUsuario, int $idGrupo): Membro | null;
    public function store(array $data): Membro;
    public function update(int $id, array $data): Membro;
    public function isUsuarioIngressado(User $usuario, int $idGrupoEstudo): bool;
    public function searchMembrosAtivosByUser(int $idUsuario, int $limite = 15, int $page = 1): Collection;
    public function searchMembrosAtivosByGrupo(int $idCurso, int $limite = 15, int $page = 1): Collection;
}
