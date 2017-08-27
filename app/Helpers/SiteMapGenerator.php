<?php
namespace App\Helpers;


use App\Services\ContentService;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SiteMapGenerator
{

    private $contentService;
    
    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function generate() {
        $sitemap = Sitemap::create(config('app.url'));

        $sitemap->add(Url::create('/')->setPriority(1));
        $sitemap->add(Url::create('/blog'));
        $sitemap->add(Url::create('/tutorials'));
        $sitemap->add(Url::create('/formations'));


        $contentForBlog = $this->contentService->getContentsForBlog();

        foreach ($contentForBlog as $content) {
            $sitemap->add(Url::create(sprintf('/blog/%s', $content->slug))->setPriority(0.9)->setLastModificationDate($content->updated_at));
        }

        $contentForTutorials = $this->contentService->getContentsForTutorials();

        foreach ($contentForTutorials as $content) {
            $sitemap->add(Url::create(sprintf('/tutorials/%s', $content->slug))->setPriority(0.9)->setLastModificationDate($content->updated_at));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}