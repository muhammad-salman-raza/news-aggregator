<?php

namespace App\DTO;

use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UserDTO extends DataTransferObject
{
    public readonly ?string $name;
    public readonly ?string $email;
    public readonly ?string $password;

    /** @var string[] */
    public readonly array $categories;
    /** @var string[] */
    public readonly array $authors;
    /** @var string[] */
    public readonly array $sources;

    /**
     * @throws UnknownProperties
     */
    public static function createFromArray(array $data): self
    {
        return new self([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
            'categories' => $data['categories'] ?? [],
            'sources' => $data['sources'] ?? [],
            'authors' => $data['authors'] ?? [],
        ]);
    }
}
