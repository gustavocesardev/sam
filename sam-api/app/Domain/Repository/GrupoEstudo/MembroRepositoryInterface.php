<?php

namespace App\Domain\Repository\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\Membro;
use App\Domain\Model\User;

interface MembroRepositoryInterface
{
    public function find(int $id): Membro;
    public function findByUsuarioAndGrupo(int $idUsuario, int $idGrupo): Membro;
    public function store(array $data): Membro;
    public function isUsuarioIngressado(User $usuario, int $idGrupoEstudo): bool;
}
