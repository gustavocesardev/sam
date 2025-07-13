<?php

namespace App\Domain\Repository\GrupoEstudo;

use App\Domain\Repository\Contracts\VisualizacaoRepositoryContract;

interface VisualizacaoRepositoryInterface extends VisualizacaoRepositoryContract
{
    public function store(int $idPublicacao, int $idMembro): void;
    public function findByMembro(int $idMembro): array;
}
