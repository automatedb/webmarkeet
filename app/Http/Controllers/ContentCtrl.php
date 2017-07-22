<?php

namespace App\Http\Controllers;

use App\Exceptions\NoFoundException;
use App\Services\ContentService;
use Illuminate\Http\Request;

class ContentCtrl extends Controller
{
    private $contentService;

    public function __construct(ContentService $contentService) {
        $this->contentService = $contentService;
    }

    /**
     * Show blog page
     * @Get("/blog")
     */
    public function index() {
        $contents = $this->contentService->getContentForBlog();

        return response()->view('Content/index', [
            'contents' => $contents
        ]);
    }

    /**
     * Show content page from blog
     * @Get("/blog/{slug}")
     */
    public function content(Request $request, $slug) {
        $view = 'Content/content';
        $data = [];
        $code = 200;

        try {
            $content = $this->contentService->getContentBySlug($slug);

            $data = [
                'content' => $content
            ];
        } catch (NoFoundException $e) {
            $view = '404';
            $code = 404;
        }

        return response()->view($view, $data, $code);
    }
}