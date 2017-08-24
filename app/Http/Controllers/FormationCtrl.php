<?php

namespace App\Http\Controllers;

use App\Exceptions\NoFoundException;
use App\Services\ContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/**
 * Class FormationCtrl
 * @package App\Http\Controllers
 * @Middleware("web")
 */
class FormationCtrl extends Controller
{
    private $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    /**
     * Show the list of formation
     * @Get("/formations")
     */
    public function index() {
        $formations = $this->contentService->getContentsForFormations();

        return response()->view('Content.formations', [
            'formations' => $formations,
            'title' => sprintf('Formation en trading automatique (trading algorithmique - %s', Config::get('app.name')),
            'description' => sprintf('%s vous propose des formations en trading automatique. Découvrez le trading algorithmique sous toutes ses formes', Config::get('app.name'))
        ]);
    }

    /**
     * Show a formation
     * @Get("/formations/{slug}")
     */
    public function formation(Request $request, $slug) {
        try {
            $content= $this->contentService->getContentBySlug($slug);
        } catch (NoFoundException $e) {
            return abort(404);
        }

        return response()->view('Content.formation', [
            'content' => $content
        ]);
    }

    /**
     * Show a formation
     * @Get("/formations/video/{slug}")
     */
    public function video(Request $request, $slug) {
        $path = storage_path('app/public/' . $slug);

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}
