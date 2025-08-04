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

    public function findByUsuario(int $idUsuario, int $limite = 15, int $page = 1): Collection
    {
        $offset = ($page - 1) * $limite;

        return ArtigoUniversitario::with(['usuario', 'usuario.curso'])
            ->where('id_usuario', $idUsuario)
            ->skip($offset)
            ->limit($limite)
            ->get();
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

    public function filtrarPorCampos(int $idInstituicao, array $filters, int $limite = 15, int $page = 1): Collection
    {
        $offset = ($page - 1) * $limite;

        return ArtigoUniversitario::query()
            ->with(['usuario', 'usuario.curso'])
            ->whereHas('usuario.curso', function ($query) use ($idInstituicao) {
                $query->where('id_instituicao', $idInstituicao);
            })
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