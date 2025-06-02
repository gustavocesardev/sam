<?php

namespace App\Infrastructure\Persistence\Repository\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\PublicacaoVisualizacao;
use App\Domain\Repository\VisualizacaoRepositoryInterface;

class PublicacaoVisualizacaoRepository implements VisualizacaoRepositoryInterface
{
    public function store(int $idPublicacao, int $idMembro): void
    {
        $data = [
            'id_publicacao' => $idPublicacao,
            'id_membro'     => $idMembro
        ];

        PublicacaoVisualizacao::create($data);
    }

    public function findByUser(int $idUsuario): array
    {
        return PublicacaoVisualizacao::where('id_membro', $idUsuario)->get()->all();
    }

    public function findByUserAndCurso(int $idUsuario, int $idCurso): array
    {
         return PublicacaoVisualizacao::whereHas('membro', function ($query) use ($idUsuario, $idCurso) {
            $query->where('id_usuario', $idUsuario)
                ->where('id_curso', $idCurso);
        })->get()->all();
    }
}
