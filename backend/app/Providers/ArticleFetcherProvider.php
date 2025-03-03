<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\GuardianApiSource;
use App\Services\NewsApiSource;
use App\Services\NewsSourceManager;
use App\Services\NYTimesApiSource;
use Illuminate\Support\ServiceProvider;

class ArticleFetcherProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(NewsApiSource::class, function ($app) {
            return new NewsApiSource(
                config('services.newsapi.api_key'),
                config('services.newsapi.api_url'),
                config('services.newsapi.sources'),
                (int)config('services.newsapi.page_size')
            );
        });

        $this->app->singleton(GuardianApiSource::class, function ($app) {
            return new GuardianApiSource(
                config('services.guardian.api_key'),
                config('services.guardian.api_url'),
                (int)config('services.guardian.page_size')
            );
        });

        $this->app->singleton(NYTimesApiSource::class, function ($app) {
            return new NYTimesApiSource(
                config('services.nytimes.api_key'),
                config('services.nytimes.api_url'),
            );
        });

        $this->app->singleton(NewsSourceManager::class, function ($app) {
            return new NewsSourceManager([
                $app->make(NewsApiSource::class),
                $app->make(GuardianApiSource::class),
                $app->make(NYTimesApiSource::class),
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
