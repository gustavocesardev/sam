<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\ArtigoUniversitario;
use App\Domain\Repository\ArtigoUniversitarioRepositoryInterface;

use Illuminate\Support\Collection;

class ArtigoUniversitarioRepository implements ArtigoUniversitarioRepositoryInterface
{
    public function find(int $id): ArtigoUniversitario
    {
        return ArtigoUniversitario::findOrFail($id);
    }

    public function findByUsuario(int $idUsuario): Collection
    {
        return ArtigoUniversitario::where('id_usuario', $idUsuario)->get();
    }

    public function store(array $data): ArtigoUniversitario
    {
        return ArtigoUniversitario::create($data)->refresh();
    }
    
    public function update(int $id, array $data): ArtigoUniversitario
    {
        $artigo = $this->find($id);
        $artigo->fill($data)->save();
        $artigo->refresh();

        return $artigo;
    }

    public function delete(int $id): bool
    {
        $artigo = $this->find($id);
        return $artigo->excluir();
    }
}