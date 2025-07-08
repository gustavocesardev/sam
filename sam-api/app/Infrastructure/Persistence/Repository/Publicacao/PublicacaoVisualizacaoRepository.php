<?php

namespace App\Infrastructure\Persistence\Repository\Publicacao;

use App\Domain\Model\Publicacao\PublicacaoVisualizacao;
use App\Domain\Repository\Publicacao\VisualizacaoRepositoryInterface;

class PublicacaoVisualizacaoRepository implements VisualizacaoRepositoryInterface
{
    public function store(int $idPublicacao, int $idUsuario): void
    {
        $data = [
            'id_publicacao' => $idPublicacao,
            'id_usuario' => $idUsuario
        ];

        PublicacaoVisualizacao::create($data);
    }

     public function findByUser(int $idUsuario): array
    {
        return PublicacaoVisualizacao::where('id_usuario', $idUsuario)->get()->all();
    }

    public function findByUserAndCurso(int $idUsuario, int $idCurso): array
    {
        return PublicacaoVisualizacao::where('id_usuario', $idUsuario)
            ->whereHas('publicacao.user.curso', function ($query) use ($idCurso) {
                $query->where('id_curso', $idCurso);
            })
            ->get()
            ->all();
    }
}
