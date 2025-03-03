<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\ArticleSearchDTO;
use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleRepository
{
    public function search(ArticleSearchDTO $articleSearchDTO): LengthAwarePaginator
    {
        $query = Article::query()
            ->with(['source', 'categories', 'authors']);

        $this->applyKeywordFilter($query, $articleSearchDTO);
        $this->applyDateFilters($query, $articleSearchDTO);
        $this->applyCategoryFilter($query, $articleSearchDTO->categories);
        $this->applySourceFilter($query, $articleSearchDTO->sources);
        $this->applyAuthorFilter($query, $articleSearchDTO->authors);

        $query->orderBy($articleSearchDTO->orderBy, $articleSearchDTO->orderDirection);

        return $query->paginate($articleSearchDTO->perPage, ['*'], 'page', $articleSearchDTO->page);
    }

    public function getUserArticles(User $user, ArticleSearchDTO $articleSearchDTO): LengthAwarePaginator
    {
        $query = Article::query();

        $this->applyCategoryFilter(
            $query,
            $user->preferredCategories()->pluck('id')->toArray()
        );
        $this->applySourceFilter(
            $query,
            $user->preferredSources()->pluck('id')->toArray()
        );
        $this->applyAuthorFilter(
            $query,
            $user->preferredAuthors()->pluck('id')->toArray()
        );

        $query->orderBy($articleSearchDTO->orderBy, $articleSearchDTO->orderDirection);

        return $query->paginate($articleSearchDTO->perPage, ['*'], 'page', $articleSearchDTO->page);
    }

    private function applyKeywordFilter($query, ArticleSearchDTO $articleSearchDTO): void
    {
        if (!empty($articleSearchDTO->keyword)) {
            $query->where(function ($q) use ($articleSearchDTO) {
                $q->where('title', 'like', "%{$articleSearchDTO->keyword}%")
                    ->orWhere('description', 'like', "%{$articleSearchDTO->keyword}%");
            });
        }
    }

    private function applyDateFilters($query, ArticleSearchDTO $articleSearchDTO): void
    {
        if (!empty($articleSearchDTO->start_date)) {
            $query->whereDate('published_at', '>=', $articleSearchDTO->start_date);
        }
        if (!empty($articleSearchDTO->end_date)) {
            $query->whereDate('published_at', '<=', $articleSearchDTO->end_date);
        }
    }

    private function applyCategoryFilter($query, array $categories): void
    {
        if (empty($categories)) {
            return;
        }

        $query->whereHas('categories', function ($q) use ($categories) {
            $q->whereIn('id', $categories);
        });
    }

    private function applySourceFilter($query, array $sources): void
    {
        if (empty($sources)) {
            return;
        }

        $query->whereHas('source', function ($q) use ($sources) {
            $q->whereIn('id', $sources);
        });
    }

    private function applyAuthorFilter($query, array $authors): void
    {
        if (empty($authors)) {
            return;
        }

        $query->whereHas('authors', function ($q) use ($authors) {
            $q->whereIn('id', $authors);
        });
    }
}
