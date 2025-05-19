<?php

namespace App\Application\Services\GrupoEstudo;

use App\Domain\Enums\ErrorContext;
use App\Domain\Exceptions\MembroExistsException;
use App\Domain\Repository\GrupoEstudo\MembroRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

class MembroService
{
    public function __construct(
        private MembroRepositoryInterface $membroRepository,
        private UserRepositoryInterface $userRepository,
    ) {}

    public function find(int $id)
    {
        $membro = $this->membroRepository->find($id);
        return $membro;
    }

    public function store(array $data)
    {
        $usuario = $this->userRepository->find($data['id_usuario']);
        $isUsuarioIngressado = $this->membroRepository->isUsuarioIngressado($usuario, $data['id_grupo_estudo']);

        if ($isUsuarioIngressado)
        {
            throw new MembroExistsException(ErrorContext::GRUPO_ESTUDO_MEMBRO,);
        }

        $membro = $this->membroRepository->store($data);
        return $membro->atualizar();
    }

    public function ativarMembro(int $id)
    {
        $membro = $this->membroRepository->find($id);
        $membro->ativar();
    }

    public function inativarMembro(int $id)
    {
        $membro = $this->membroRepository->find($id);
        $membro->inativar();
    }
}