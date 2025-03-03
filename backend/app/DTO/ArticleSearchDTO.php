<?php

declare(strict_types=1);

namespace App\DTO;

use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class ArticleSearchDTO extends DataTransferObject
{
    public readonly ?string $keyword;
    public readonly ?string $start_date;
    public readonly ?string $end_date;
    /** @var string[]|null */
    public readonly ?array $categories;
    /** @var string[]|null */
    public readonly ?array $sources;
    /** @var string[]|null */
    public readonly ?array $authors;
    public readonly int $page;
    public readonly int $perPage;
    public readonly string $orderBy;
    public readonly string $orderDirection;

    /**
     * @throws UnknownProperties
     */
    public static function createFromArray(array $data): self
    {
        return new self([
            'keyword' => $data['keyword'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'categories' => $data['categories'] ?? [],
            'sources' => $data['sources'] ?? [],
            'authors' => $data['authors'] ?? [],
            'page' => isset($data['page']) ? (int)$data['page'] : 1,
            'perPage' => isset($data['per_page']) ? (int)$data['per_page'] : 10,
            'orderBy' => $data['order_by'] ?? 'published_at',
            'orderDirection' => $data['order_direction'] ?? 'desc',
        ]);
    }
}
