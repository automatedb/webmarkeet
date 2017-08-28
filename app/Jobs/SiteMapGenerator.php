<?php

namespace App\Jobs;

use App\Models\Chapter;
use App\Models\Content;
use App\Services\ContentService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use League\CommonMark\Converter;

class SiteMapGenerator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $siteMapGenerator;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ContentService $contentService)
    {
        $this->siteMapGenerator = new \App\Helpers\SiteMapGenerator($contentService);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Sitemap generator running');

        $this->siteMapGenerator->generate();
    }
}
