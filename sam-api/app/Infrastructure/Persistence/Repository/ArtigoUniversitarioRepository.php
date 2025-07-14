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

    public function filtrarPorCampos(array $filters, int $limite = 15, int $page = 1): Collection
    {
        $offset = ($page - 1) * $limite;

        return ArtigoUniversitario::query()
            ->when(!empty($filters['titulo']), function ($query) use ($filters) {
                $query->where('titulo', 'ILIKE', '%' . $filters['titulo'] . '%');
            })
            ->when(!empty($filters['hashtags']), function ($query) use ($filters) {
                $hashtags = array_filter(
                    array_map(fn($h) => trim(ltrim($h, '#')), explode(' ', $filters['hashtags']))
                );

                $query->where(function ($subQuery) use ($hashtags) {
                    foreach ($hashtags as $hashtag) {
                        $subQuery->orWhere('palavras_chave', 'ILIKE', '%' . $hashtag . '%');
                    }
                });
            })
            ->skip($offset)
            ->limit($limite)
            ->get();
    }
}