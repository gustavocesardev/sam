<?php

namespace App\Domain\VO\Recomendacao;

class KeywordsRelevantes
{
    public function __construct(
        public array $curtidas,
        public array $visualizadas
    ) {}
}
