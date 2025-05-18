<?php

namespace App\Application\Services;

use App\Domain\Model\Abstract\PublicacaoAbstract;
use App\Domain\Repository\KeywordRepositoryInterface;

use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\Filters\StopWordsFilter;

class KeywordService
{
    public function __construct(private KeywordRepositoryInterface $repository) {}

    public function publicacaoExtractAndStore(PublicacaoAbstract $publicacao)
    {
        $keywords = $this->extractWithFrequency($publicacao->texto);

        $this->repository->saveMany($publicacao->id, $keywords);
    }

    private function extractWithFrequency(string $text): array
    {
        $normalizedText = $this->normalizeText($text);

        $tokenizer = new GeneralTokenizer();
        $tokens = $tokenizer->tokenize($normalizedText);

        $stopWordsFilter = new StopWordsFilter(['pt']);
        $filtered = array_filter(array_map([$stopWordsFilter, 'transform'], $tokens), fn($w) => strlen($w) > 2);

        $frequency = array_count_values($filtered);

        return $frequency;
    }

    private function normalizeText(string $text): string
    {
        $text = preg_replace('/[^\w\s]/', '', $text);

        return strtolower($text);
    }
}
