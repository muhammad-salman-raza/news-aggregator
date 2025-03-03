<?php

namespace App\Console\Commands;

use App\Services\NewsSourceManager;
use Illuminate\Console\Command;

class FetchNewsCommand extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch and save dummy news articles';

    public function __construct(private readonly NewsSourceManager $newsSourceManager)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->newsSourceManager->dispatchJobs();

        $this->info('Jobs to fetch and save articles have been dispatched for all sources.');

        return Command::SUCCESS;
    }
}
