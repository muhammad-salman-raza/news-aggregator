<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ArticleDTO;
use Illuminate\Support\Collection;

interface NewsSourceInterface
{
    /**
     * @return Collection<ArticleDTO>
     */
    public function fetchArticles(): Collection;

    public function canHandle(string $sourceName): bool;

    public function getSource(): string;
}
