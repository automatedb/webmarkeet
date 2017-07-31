<?php

namespace App\Http\Controllers;

use App\Exceptions\NoFoundException;
use App\Exceptions\RequireFieldsException;
use App\Exceptions\SlugAlreadyExistsException;
use App\Helpers\ImgHelper;
use App\Models\Content;
use App\Services\ContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use League\Flysystem\Exception;

/**
 * Class ContentCtrl
 * @package App\Http\Controllers
 * @Middleware("web")
 */
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
            'contents' => $contents,
            'title' => sprintf('Blog - %s', Config::get('app.name')),
            'description' => Config::get('app.description'),
            'image' => '/img/robots-trading.jpg'
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
                'content' => $content,
                'title' => sprintf('%s - %s', $content->title, Config::get('app.name')),
                'description' => strip_tags(str_limit($content->content, 160)),
                'image' => ImgHelper::link($content->thumbnail, $content->id, 1200)
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
    public function contents(Request $request) {
        return response()->view('Content.contents', [
            'contents' => $this->contentService->getContents(),
            'alert' => $request->session()->get('alert')
        ]);
    }

    /**
     * Show content form
     * @Get("/app/admin/contents/add")
     */
    public function add(Request $request) {
        return response()->view('Content.add', [
            'status' => config('content.status'),
            'types' => config('content.type'),
            'alert' => $request->session()->get('alert')
        ]);
    }

    /**
     * Post new content
     * @Post("/app/admin/contents/add")
     */
    public function postAdd(Request $request) {
        $thumbnail = [];
        $rules = [
            'title' => 'required',
            'slug' => 'required',
            'status' => 'required',
            'type' => 'required'
        ];

        $values = $request->all();

        $validation = Validator::make($values, $rules);

        if($validation->fails()) {
            throw new RequireFieldsException('Fields required');
        }

        if($request->hasFile('thumbnail')) {
            $request->file('thumbnail')->store(config('content.uploadDirectory'));

            $thumbnail[ContentService::HASH_NAME] = $request->thumbnail->hashName();
            $thumbnail[ContentService::ORIGINAL_NAME] = $request->thumbnail->getClientOriginalName();
        }

        $user = Auth::user();

        try {
            $contentId = $this->contentService->add(
                $user->id,
                $values[Content::$TITLE],
                $values[Content::$SLUG],
                $values[Content::$STATUS],
                $values[Content::$TYPE],
                $values[Content::$CONTENT],
                $thumbnail);
        } catch (SlugAlreadyExistsException $e) {
            $request->session()->flash('alert', [
                'message' => "L'url que vous essayez de saisir existe déjà pour un autre contenu.",
                'type' => 'warning'
            ]);

            return redirect()->back()->withInput();
        }

        return redirect()->action('ContentCtrl@modify', [ 'id' => $contentId ]);
    }

    /**
     * Show content form to modify
     * @Get("/app/admin/contents/modify/{id}")
     */
    public function modify(Request $request, $id) {
        return response()->view('Content.modify', [
            'content' => $this->contentService->getContent($id),
            'status' => config('content.status'),
            'types' => config('content.type'),
            'alert' => $request->session()->get('alert')
        ]);
    }

    /**
     * Post modifications
     * @Post("/app/admin/contents/modify/{id}")
     */
    public function postModify(Request $request) {
        $thumbnail = [];
        $rules = [
            'title' => 'required',
            'slug' => 'required',
            'status' => 'required',
            'type' => 'required'
        ];

        $values = $request->all();

        $validation = Validator::make($values, $rules);

        if($validation->fails()) {
            throw new RequireFieldsException('Fields required');
        }

        if($request->hasFile('thumbnail')) {
            $request->file('thumbnail')->store(config('content.uploadDirectory'));

            $thumbnail[ContentService::HASH_NAME] = $request->thumbnail->hashName();
            $thumbnail[ContentService::ORIGINAL_NAME] = $request->thumbnail->getClientOriginalName();
        }

        try {
            $this->contentService->update(
                intval($values['id']),
                $values[Content::$TITLE],
                $values[Content::$SLUG],
                $values[Content::$STATUS],
                $values[Content::$TYPE],
                $values[Content::$CONTENT],
                $thumbnail
            );

            $request->session()->flash('alert', [
                'message' => 'Votre contenu a été mis à jour.',
                'type' => 'success'
            ]);
        } catch (Exception $e) {
            $request->session()->flash('alert', [
                'message' => 'Une erreur est survenue pendant le traitement.',
                'type' => 'danger'
            ]);
        }

        return redirect()->back();
    }

    /**
     * Delete content
     * @Get("/app/admin/contents/delete/{id}")
     */
    public function delete(Request $request, $id) {
        $data = [
            'message' => 'Contenu supprimé.',
            'type' => 'success'
        ];

        if(!$this->contentService->delete($id)) {
            $data = [
                'message' => 'Une erreur est survenue.',
                'type' => 'danger'
            ];
        }

        $request->session()->flash('alert', $data);

        return redirect()->back();
    }
}