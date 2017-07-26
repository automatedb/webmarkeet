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

    /**
     * Show all contents
     * @Get("/app/admin/contents")
     */
    public function contents() {
        return response()->view('Content.contents', [
            'contents' => $this->contentService->getContents()
        ]);
    }

    /**
     * Show content form
     * @Get("/app/admin/contents/add")
     */
    public function add() {
        return response()->view('Content.add');
    }

    /**
     * Show content form to modify
     * @Get("/app/admin/contents/modify/{id}")
     */
    public function modify() {
        return response()->view('Content.modify');
    }

    /**
     * Delete content
     * @Get("/app/admin/contents/delete/{id}")
     */
    public function delete(Request $request, $id) {

    }
}