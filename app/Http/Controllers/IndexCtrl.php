<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use Illuminate\Support\Facades\Config;

class IndexCtrl extends Controller
{
    private $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    /**
     * Show index page
     * @Get("/")
     */
    public function index() {
        $contents = $this->contentService->getRecentContentsForBlog();
        $tutorials = $this->contentService->getRecentContentsForTutorial();

        return view('welcome', [
            'contents' => $contents,
            'tutorials' => $tutorials,
            'thumbnail' => Config::get('app.thumbnail')
        ]);
    }
}