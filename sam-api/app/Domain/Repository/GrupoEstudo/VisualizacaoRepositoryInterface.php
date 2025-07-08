<?php

namespace App\Domain\Repository\GrupoEstudo;

use App\Domain\Repository\Abstract\VisualizacaoRepositoryAbstract;

interface VisualizacaoRepositoryInterface extends VisualizacaoRepositoryAbstract
{
    public function store(int $idPublicacao, int $idMembro): void;
    public function findByMembro(int $idMembro): array;
}
