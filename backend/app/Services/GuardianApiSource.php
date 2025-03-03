<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ArticleDTO;
use App\DTO\SourceDTO;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class GuardianApiSource implements NewsSourceInterface
{
    private const API_SOURCE = 'guardian';
    private const SOURCE_NAME = 'Guardian';
    private Client $client;

    public function __construct(
        private readonly string $apiKey,
        private readonly string $apiUrl,
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

        $firstPageArticles = $this->fetchPage($yesterday, 1);
        $articles = $articles->merge($firstPageArticles['articles']);
        $totalResults = $firstPageArticles['totalResults'];
        $totalPages = ceil($totalResults / $this->pageSize);


        for ($page = 2; $page <= $totalPages; $page++) {
            sleep(1); // Delay for 1 second between requests
            $nextPageArticles = $this->fetchPage($yesterday, $page);
            $articles = $articles->merge($nextPageArticles['articles']);
        }

        return $articles;
    }

    private function fetchPage(string $yesterday, int $page): array
    {
        $url = $this->buildUrl($yesterday, $page);
        $response = $this->client->get($url);
        $data = json_decode($response->getBody()->getContents(), true);

        return [
            'articles' => $this->mapArticles($data['response']['results'] ?? []),
            'totalResults' => $data['response']['total'] ?? 0, // Return totalResults
        ];
    }

    private function buildUrl(string $yesterday, int $page): string
    {
        return "{$this->apiUrl}?from-date={$yesterday}&to-date={$yesterday}&use-date=published&api-key={$this->apiKey}&page-size={$this->pageSize}&page={$page}";
    }

    private function mapArticles(array $articlesData): Collection
    {
        $sourceDTO = new SourceDTO(
            externalId: self::API_SOURCE,
            name: self::SOURCE_NAME
        );

        return collect(array_map(function ($article) use ($sourceDTO) {
            return new ArticleDTO(
                title: $article['webTitle'],
                description: $article['webTitle'],
                content: $article['webTitle'],
                url: $article['webUrl'],
                urlToImage: '',
                publishedAt: $article['webPublicationDate'],
                source: $sourceDTO,
                categoryNames: [$article['pillarName'] ?? 'General'],
                authorNames: [],
                rawResponse: null
            );
        }, $articlesData));
    }
}
