<?php

namespace App\Application\Services\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\Membro;
use App\Domain\Repository\GrupoEstudo\MembroRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

class MembroService
{
    public function __construct(
        private MembroRepositoryInterface $membroRepository,
        private UserRepositoryInterface $userRepository,
    ) {}

    public function find(int $id): Membro|null
    {
        $membro = $this->membroRepository->find($id);
        return $membro;
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
}