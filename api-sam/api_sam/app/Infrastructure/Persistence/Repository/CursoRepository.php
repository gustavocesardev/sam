<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Repository\CursoRepositoryInterface;
use App\Domain\Model\Curso;

use Illuminate\Support\Collection;

class CursoRepository implements CursoRepositoryInterface
{
    public function findAll(): Collection
    {  
        return Curso::all();
    }

    public function find(int $id): Curso
    {
        $curso = Curso::findOrFail($id);
        return $curso;
    }

    public function store(array $data): Curso
    {
        $curso = Curso::create($data);
        $curso->refresh();
        return $curso;
    }

    public function update(int $id, array $data): Curso
    {
        $curso = Curso::findOrFail($id);
        $curso->fill($data)->save();
        $curso->refresh();

        return $curso;
    }

    public function delete(int $id): bool
    {
        $curso = Curso::findOrFail($id);
        return $curso->excluir();
    }
}