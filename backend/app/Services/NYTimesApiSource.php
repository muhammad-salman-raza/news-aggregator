<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ArticleDTO;
use App\DTO\SourceDTO;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class NYTimesApiSource implements NewsSourceInterface
{
    private const API_SOURCE = 'NY Times';
    private Client $client;

    public function __construct(private readonly string $apiKey, private readonly string $apiUrl)
    {
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
        $articles = collect();
        $page = 1;

        while (true) {
            $pageArticles = $this->fetchPage($page);
            if (empty($pageArticles['docs'])) {
                break;
            }
            $articles = $articles->merge($this->mapArticles($pageArticles['docs']));
            $page++;

            break; // need account upgrade to have multiple hits
        }

        return $articles;
    }

    private function fetchPage(int $page): array
    {
        $url = "{$this->apiUrl}?api-key={$this->apiKey}&page={$page}";
        $response = $this->client->get($url);
        return json_decode($response->getBody()->getContents(), true)['response'] ?? [];
    }

    private function mapArticles(array $articlesData): Collection
    {
        return collect(array_map(function ($article) {
            $sourceDTO = new SourceDTO(
                externalId: 'nyt',
                name: self::API_SOURCE
            );

            return new ArticleDTO(
                title: $article['headline']['main'] ?? 'No Title',
                description: $article['abstract'] ?? '',
                content: $article['lead_paragraph'] ?? '',
                url: $article['web_url'] ?? '',
                urlToImage: $this->getFirstImageUrl($article['multimedia'] ?? []),
                publishedAt: $article['pub_date'] ?? null,
                source: $sourceDTO,
                categoryNames: ['General'],
                authorNames: [],
                rawResponse: null
            );
        }, $articlesData));
    }

    private function getFirstImageUrl(array $multimedia): ?string
    {
        return !empty($multimedia) ? 'https://static01.nyt.com/' . $multimedia[0]['url'] : '';
    }
}
