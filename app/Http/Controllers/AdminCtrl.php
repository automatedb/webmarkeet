<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use Illuminate\Http\Request;

/**
 * @Middleware("web")
 */
class AdminCtrl extends Controller
{
    private $contentService;

    public function __construct(ContentService $contentService) {
        $this->contentService = $contentService;
    }

    /**
     * @Get("/admin")
     * @Middleware("admin")
     */
    public function index(Request $request) {
        return response()->view('Admin.index', [
            'contents' => $this->contentService->getContents(),
            'alert' => $request->session()->get('alert')
        ]);
    }
}
