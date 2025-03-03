<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ArticleDTO;
use App\DTO\SourceDTO;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;

class NewsApiSource implements NewsSourceInterface
{
    private const API_SOURCE = 'newsapi';
    private Client $client;

    public function __construct(
        private readonly string $apiKey,
        private readonly string $apiUrl,
        private readonly string $sources,
        private readonly int $pageSize
    ) {
        $this->client = new Client();
    }

    public function canHandle(string $sourceName): bool
    {
        return $sourceName === self::API_SOURCE;
    }

    public function getSource(): string
    {
        return self::API_SOURCE;
    }

    public function fetchArticles(): Collection
    {
        $yesterday = now()->subDay()->format('Y-m-d');
        $articles = collect();
        $totalResults = 0;
        $totalPages = 0;

        // Fetch the first page synchronously
        $firstPageArticles = $this->fetchPage($yesterday, 1);
        $articles = $articles->merge($firstPageArticles['articles']);
        $totalResults = $firstPageArticles['totalResults'];
        $totalPages = ceil($totalResults / $this->pageSize);

        /**
         * For multiple calls account upgrade is needed so skipping multiple calls
         *
        for ($page = 2; $page <= $totalPages; $page++) {
            $nextPageArticles = $this->fetchPage($yesterday, $page);
            $articles = $articles->merge($nextPageArticles['articles']);
        } **/

        return $articles;
    }

    private function fetchPage(string $yesterday, int $page): array
    {
        $url = $this->buildUrl($yesterday, $page);
        $response = $this->client->get($url);
        $data = json_decode($response->getBody()->getContents(), true);

        return [
            'articles' => $this->mapArticles($data['articles'] ?? [], $data),
            'totalResults' => $data['totalResults'] ?? 0, // Return totalResults
        ];
    }

    private function buildUrl(string $yesterday, int $page): string
    {
        return "{$this->apiUrl}?from={$yesterday}&to={$yesterday}&apiKey={$this->apiKey}&sources={$this->sources}&pageSize={$this->pageSize}&page={$page}";
    }

    private function mapArticles(array $articlesData, array $data): Collection
    {
        $sourceDTO = new SourceDTO(
            externalId: $data['articles'][0]['source']['id'] ?? 'unknown_source',
            name: $data['articles'][0]['source']['name'] ?? 'Unknown Source'
        );

        return collect(array_map(function ($article) use ($sourceDTO) {
            return new ArticleDTO(
                title: $article['title'],
                description: $article['description'],
                content: $article['content'],
                url: $article['url'],
                urlToImage: $article['urlToImage'],
                publishedAt: $article['publishedAt'],
                source: $sourceDTO,
                categoryNames: ['General'],
                authorNames: array_map('trim', explode(',', $article['author'] ?? 'Unknown')),
                rawResponse: null
            );
        }, $articlesData));
    }
}
