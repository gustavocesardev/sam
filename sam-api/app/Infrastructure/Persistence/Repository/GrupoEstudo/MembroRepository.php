<?php

namespace App\Infrastructure\Persistence\Repository\GrupoEstudo;

use App\Domain\Model\User;
use App\Domain\Model\GrupoEstudo\Membro;
use App\Domain\Repository\GrupoEstudo\MembroRepositoryInterface;

class MembroRepository implements MembroRepositoryInterface
{
    public function find(int $id): ?Membro
    {
        return Membro::findOrFail($id);
    }

     public function findByUsuarioAndGrupo(int $idUsuario, int $idGrupoEstudo): ?Membro
    {
        return Membro::where('id_usuario', $idUsuario)
                     ->where('id_grupo_estudo', $idGrupoEstudo)
                     ->first();
    }

    public function store(array $data): Membro
    {
        return Membro::create($data);
    }

    public function isUsuarioIngressado(User $usuario, int $idGrupoEstudo): bool
    {
        $isIngressado = $usuario->membrosGrupoEstudo()
                        ->where('id_grupo_estudo',  $idGrupoEstudo)
                        ->exists();

        return $isIngressado;
    }

    public function update(int $id, array $data): Membro
    {
        $membro = $this->find($id);
        $membro->update($data);

        return $membro;
    }
}