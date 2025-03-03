<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\SimpleSearchDTO;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryRepository
{
    public function search(SimpleSearchDTO $dto): LengthAwarePaginator
    {
        $query = Category::query();

        if (!empty($dto->keyword)) {
            $query->where('name', 'like', "%{$dto->keyword}%");
        }

        $query->orderBy('name', $dto->orderDirection);

        return $query->paginate($dto->perPage, ['*'], 'page', $dto->page);
    }
}
