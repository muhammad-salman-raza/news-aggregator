<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ArticleDTO;
use App\Jobs\FetchAndSaveArticlesJob;
use Illuminate\Support\Collection;

class NewsSourceManager
{
    /**
     * @var array<NewsSourceInterface>
     */
    private array $sources;

    public function __construct(array $sources)
    {
        $this->sources = $sources;
    }

    public function dispatchJobs(): void
    {
        foreach ($this->sources as $source) {
            FetchAndSaveArticlesJob::dispatch($source->getSource());
        }
    }

    /**
     * @return Collection<ArticleDTO>
     */
    public function fetchArticlesForSource(string $sourceName): Collection
    {
        foreach ($this->sources as $source) {
            if (!$source->canHandle($sourceName)) {
                continue;
            }

            return $source->fetchArticles();
        }

        return new Collection([]);
    }
}
