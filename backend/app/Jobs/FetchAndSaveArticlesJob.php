<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\ArticleSaver;
use App\Services\NewsSourceManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchAndSaveArticlesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly string $sourceName)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ArticleSaver $articleSaver, NewsSourceManager $newsSourceManager): void
    {
        try {
            $articleDTOs = $newsSourceManager->fetchArticlesForSource($this->sourceName);
            $articleSaver->saveArticles($articleDTOs);
        } catch (\Throwable $exception) {
            dd($exception->getMessage());
        }

    }
}
