<?php

namespace App\Application\Services\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\Membro;
use App\Domain\Repository\GrupoEstudo\MembroRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VO\Auth\AuthenticatedUser;

use Illuminate\Support\Collection;

class MembroService
{
    public function __construct(
        private MembroRepositoryInterface $membroRepository,
        private UserRepositoryInterface $userRepository,
    ) {}

    public function find(int $id): Membro | null
    {
        $membro = $this->membroRepository->find($id);
        return $membro;
    }

    public function findByUsuarioAndGrupo(AuthenticatedUser $user, int $idGrupoEstudo): Membro | null
    {
        return $this->membroRepository->findByUsuarioAndGrupo($user->id(), $idGrupoEstudo);
    }

    public function store(array $data): Membro
    {
        $usuario = $this->userRepository->find($data['id_usuario']);
        $isUsuarioIngressado = $this->membroRepository->isUsuarioIngressado($usuario, $data['id_grupo_estudo']);

        if ($isUsuarioIngressado)
        {
            $membro = $this->membroRepository->findByUsuarioAndGrupo($usuario->id, $data['id_grupo_estudo']);
            $membro = $this->membroRepository->update($membro->id, $data);

            return $membro;
        }

        $membro = $this->membroRepository->store($data);
        return $membro;
    }

    public function listarMembrosByUsuario(AuthenticatedUser $user, int $limite = 15, int $page = 1): Collection
    {
        return $this->membroRepository->searchMembrosAtivosByUser($user->id(), $limite, $page);
    }

    public function listarMembrosAtivosByGrupo(int $idGrupo, int $limite = 15, int $page = 1): Collection
    {
        return $this->membroRepository->searchMembrosAtivosByGrupo($idGrupo, $limite, $page);
    }
}