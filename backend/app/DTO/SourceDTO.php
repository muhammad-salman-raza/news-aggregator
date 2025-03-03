<?php

declare(strict_types=1);

namespace App\DTO;

use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SourceDTO extends DataTransferObject
{
    public readonly string $externalId;
    public readonly string $name;

    /**
     * @throws UnknownProperties
     */
    public static function createFromArray(array $data): self
    {
        return new self([
            'externalId' => $data['external_id'],
            'name' => $data['name'],
        ]);
    }
}
