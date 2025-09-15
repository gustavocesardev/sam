<?php

namespace App\Domain\Services\Abstract;

use App\Domain\Model\Abstract\PublicacaoAbstract;
use App\Domain\Repository\Contracts\KeywordRepositoryContract;
use App\Domain\VO\Recomendacao\Abstract\Interacoes;
use App\Domain\VO\Recomendacao\KeywordsRelevantes;

use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\Filters\StopWordsFilter;

abstract class KeywordServiceAbstract
{
    public function __construct(private KeywordRepositoryContract $keywordRepository) {}

    public function publicacaoExtractAndStore(PublicacaoAbstract $publicacao): void
    {
        $keywords = $this->extractWithFrequency($publicacao->texto);

        $this->keywordRepository->saveMany($publicacao->id, $keywords);
    }

    public function extractWithFrequency(string $text): array
    {
        $normalizedText = $this->normalizeText($text);

        $tokenizer = new GeneralTokenizer();
        $tokens = $tokenizer->tokenize($normalizedText);

        $stopWordsFilter = new StopWordsFilter(['pt']);
        $filtered = array_filter(array_map([$stopWordsFilter, 'transform'], $tokens), fn($w) => strlen($w) > 2);

        $frequency = array_count_values(array: $filtered);

        return $frequency;
    }

    private function normalizeText(string $text): string
    {
        $text = preg_replace('/[^\w\s]/', '', $text);

        return strtolower($text);
    }

    public function extractKeywordsByInteracoes(Interacoes $interacoes): KeywordsRelevantes
    {
        $curtidasIds = $interacoes->getPublicacoesCurtidasIds();
        $visualizadasIds = $interacoes->getPublicacoesVisualizadasIds();

        $keywordsCurtidas = $this->buscarKeywords($curtidasIds);
        $keywordsVisualizadas = array_diff(
            $this->buscarKeywords($visualizadasIds),
            $keywordsCurtidas
        );

        return new KeywordsRelevantes($keywordsCurtidas, $keywordsVisualizadas);
    }

    private function buscarKeywords(array $ids): array
    {
        $all = [];
        foreach ($ids as $id) {
            $all = array_merge($all, $this->keywordRepository->findByPublicacao(idPublicacao: $id));
        }

        return array_unique($all);
    }
}
