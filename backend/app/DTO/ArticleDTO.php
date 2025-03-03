<?php

declare(strict_types=1);

namespace App\DTO;

use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class ArticleDTO extends DataTransferObject
{
    public readonly string $title;
    public readonly string $description;
    public readonly string $content;
    public readonly string $url;
    public readonly string $urlToImage;
    public readonly string $publishedAt;
    public readonly SourceDTO $source;
    /** @var string[] */
    public readonly array $categoryNames;
    /** @var string[] */
    public readonly array $authorNames;
    public readonly ?array $rawResponse;

    /**
     * @throws UnknownProperties
     */
    public static function createFromArray(array $data): self
    {
        return new self([
            'id' => $data['id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'content' => $data['content'],
            'url' => $data['url'],
            'urlToImage' => $data['url_to_image'],
            'publishedAt' => $data['published_at'],
            'source' => SourceDTO::createFromArray($data['source']),
            'categoryNames' => $data['categoryNames'] ?? [],
            'authorNames' => $data['authorNames'] ?? [],
            'rawResponse' => $data['raw_response'] ?? null,
        ]);
    }
}
