<?php

namespace App\Application\Services;

use App\Domain\Model\Curso;
use App\Domain\Repository\CursoRepositoryInterface;
use Illuminate\Support\Collection;

class CursoService
{
    public function __construct(private CursoRepositoryInterface $cursoRepository) {}
    
    /**
     * TODO: Adicionar paginação
     * Summary of listAll
     * @return Collection
     */
    public function listAll(): Collection
    {
        return $this->cursoRepository->findAll();
    }

    public function store(array $data): Curso
    {
        return $this->cursoRepository->store($data);
    }

    public function find(int $id): Curso
    {
        return $this->cursoRepository->find($id);
    }

    public function update(int $id, array $data): Curso
    {
        return $this->cursoRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->cursoRepository->delete($id);
    }

}