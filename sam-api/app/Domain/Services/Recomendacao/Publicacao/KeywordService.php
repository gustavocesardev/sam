<?php

namespace App\Domain\Services\Recomendacao\Publicacao;

use App\Domain\Repository\Publicacao\KeywordRepositoryInterface;
use App\Domain\Services\Abstract\KeywordServiceAbstract;

class KeywordService extends KeywordServiceAbstract
{
    public function __construct(KeywordRepositoryInterface $keywordRepository)
    {
        parent::__construct($keywordRepository);
    }
}