<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use App\Services\FormationService;
use Illuminate\Http\Request;

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
            'formations' => $formations
        ]);
    }
}
