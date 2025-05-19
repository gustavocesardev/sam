<?php

namespace App\Infrastructure\Persistence\Repository\Publicacao;

use App\Domain\Model\Publicacao\PublicacaoVisualizacao;
use App\Domain\Repository\VisualizacaoRepositoryInterface;

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
}
