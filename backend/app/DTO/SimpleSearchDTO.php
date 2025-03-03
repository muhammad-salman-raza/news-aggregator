<?php

declare(strict_types=1);

namespace App\DTO;

use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SimpleSearchDTO extends DataTransferObject
{
    public readonly ?string $keyword;
    public readonly int $page;
    public readonly int $perPage;
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
            'orderDirection' => $data['order_direction'] ?? 'asc',
        ]);
    }
}
