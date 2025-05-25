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
}
