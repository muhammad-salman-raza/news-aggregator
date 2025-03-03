<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\SimpleSearchDTO;
use App\Models\Author;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AuthorRepository
{
    public function search(SimpleSearchDTO $dto): LengthAwarePaginator
    {
        $query = Author::query();

        if (!empty($dto->keyword)) {
            $query->where('name', 'like', "%{$dto->keyword}%");
        }

        $query->orderBy('name', $dto->orderDirection);

        return $query->paginate($dto->perPage, ['*'], 'page', $dto->page);
    }
}
