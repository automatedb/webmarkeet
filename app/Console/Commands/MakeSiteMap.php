<?php

namespace App\Console\Commands;

use App\Helpers\SiteMapGenerator;
use Illuminate\Console\Command;

class MakeSiteMap extends Command
{
    private $siteMapGenerator;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Can generate sitemap';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SiteMapGenerator $siteMapGenerator)
    {
        parent::__construct();

        $this->siteMapGenerator = $siteMapGenerator;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->siteMapGenerator->generate();
    }
}
