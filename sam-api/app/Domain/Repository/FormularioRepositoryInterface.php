<?php

namespace App\Domain\Repository; 

use App\Domain\Model\Formulario;
use Illuminate\Support\Collection;

interface FormularioRepositoryInterface
{
    public function find(int $id): Formulario;
    public function findAtivoByUsuario(int $idUsuario): Collection;
    public function store(array $data): Formulario;
    public function update(int $id, array $data): Formulario;
    public function delete(int $id): bool;
    public function filtrarPorCampos(int $idInstituicao, array $filters, int $limite = 15, int $page = 1): Collection;
}
