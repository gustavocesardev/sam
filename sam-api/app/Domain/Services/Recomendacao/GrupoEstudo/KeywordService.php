<?php

namespace App\Domain\Services\Recomendacao\GrupoEstudo;

use App\Domain\Repository\GrupoEstudo\KeywordRepositoryInterface;
use App\Domain\Services\Abstract\KeywordServiceAbstract;

class KeywordService extends KeywordServiceAbstract
{
    public function __construct(KeywordRepositoryInterface $keywordRepository)
    {
        parent::__construct($keywordRepository);
    }
}