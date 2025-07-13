<?php

namespace App\Application\Services;

use App\Domain\Model\Formulario;
use App\Domain\Repository\FormularioRepositoryInterface;

class FormularioService
{
    public function __construct(private FormularioRepositoryInterface $formularioRepository) {}

    public function store(array $data): Formulario
    {
        return $this->formularioRepository->store($data);
    }

    public function find(int $id): Formulario
    {
        return $this->formularioRepository->find($id);
    }

    public function update(int $id, array $data): Formulario
    {
        return $this->formularioRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->formularioRepository->delete($id);
    }
}