<?php

namespace App\Http\Controllers;

use App\Exceptions\NoFoundException;
use App\Exceptions\RequireFieldsException;
use App\Exceptions\SlugAlreadyExistsException;
use App\Helpers\ImgHelper;
use App\Models\Content;
use App\Models\Source;
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
        $contents = $this->contentService->getContentsForBlog();

        return response()->view('Content/index', [
            'contents' => $contents,
            'title' => sprintf('Blog - %s', Config::get('app.name')),
            'description' => Config::get('app.description'),
            'thumbnail' => Config::get('app.thumbnail')
        ]);
    }

    /**
     * Show content page from blog
     * @Get("/blog/{slug}")
     */
    public function content(Request $request, string $slug) {
        $view = 'Content.content';
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
            return abort(404);
        }

        return response()->view($view, $data, $code);
    }

    /**
     * Able to download source
     * @Get("/blog/{slug}/download")
     * @Middleware("download")
     */
    public function downloadSources(Request $request, string $slug) {
        try {
            $content = $this->contentService->getContentBySlug($slug);

            if(!$content->sources()->count()) {
                return abort(404);
            }
        } catch (NoFoundException $e) {
            return abort(404);
        }

        return $this->downloadFile($content->sources()->first());
    }

    /**
     * Able to download source
     * @Get("/formation/chapter/{id}/type/{type}")
     * @Middleware("download")
     */
    public function downloadChaptersSources(Request $request, string $id, string $type) {
        try {
            $source = $this->contentService->getSourceFromChapter(intval($id), $type);

            if(empty($source)) {
                return abort(404);
            }
        } catch (NoFoundException $e) {
            return abort(404);
        }

        return $this->downloadFile($source);
    }

    /**
     * Return file from source
     */
    private function downloadFile($source) {
        $file = storage_path(sprintf('app/public/%s', $source[Source::NAME]));

        $typeMime = mime_content_type(storage_path(sprintf('app/public/%s', $source[Source::NAME])));

        return response()->download($file, null, [
            'Content-Type' => $typeMime
        ]);
    }

    /**
     * @Get("/tutorials")
     */
    public function tutorials() {
        $contents = $this->contentService->getContentsForTutorials();

        $contents = $contents->toArray();

        $first = array_shift($contents);

        return response()->view('Content.tutorials', [
            'firstContent' => $first,
            'contents' => $contents,
            'title' => sprintf('Tutoriels - %s', Config::get('app.name')),
            'description' => Config::get('app.description'),
            'thumbnail' => Config::get('app.thumbnail')
        ]);
    }

    /**
     * @Get("/tutorials/{slug}")
     */
    public function tutorial(Request $request, $slug) {
        $view = 'Content.tutorial';
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
            return abort(404);
        }

        return response()->view($view, $data, $code);
    }

    /**
     * Show content form
     * @Get("/admin/contents/add")
     * @Middleware("admin")
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
     * @Post("/admin/contents/add")
     * @Middleware("admin")
     */
    public function postAdd(Request $request) {
        $thumbnail = [];
        $sources = [];
        $rules = [
            'title' => 'required',
            'slug' => 'required',
            'status' => 'required'
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

        if($request->hasFile('sources')) {
            $request->file('sources')->store(config('content.uploadDirectory'));

            $sources[ContentService::HASH_NAME] = $request->sources->hashName();
            $sources[ContentService::ORIGINAL_NAME] = $request->sources->getClientOriginalName();
        }

        $chapters = [];

        if(!empty($values['chapters'])) {
            $chapters = $values['chapters'];
        }
        
        $user = Auth::user();

        try {
            $contentId = $this->contentService->add(
                $user->id,
                $values[Content::$TITLE],
                $values[Content::$SLUG],
                $values[Content::$STATUS],
                $values[Content::$CONTENT],
                $values[Content::$VIDEO_ID],
                $thumbnail,
                $sources,
                $chapters);
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
     * @Get("/admin/contents/modify/{id}")
     * @Middleware("admin")
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
     * @Post("/admin/contents/modify/{id}")
     * @Middleware("admin")
     */
    public function postModify(Request $request) {
        $thumbnail = [];
        $source = [];
        $rules = [
            'title' => 'required',
            'slug' => 'required',
            'status' => 'required'
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

        if($request->hasFile('sources')) {
            $request->file('sources')->store(config('content.uploadDirectory'));

            $source[ContentService::HASH_NAME] = $request->sources->hashName();
            $source[ContentService::ORIGINAL_NAME] = $request->sources->getClientOriginalName();
        }

        $chapters = [];

        if(!empty($values['chapters'])) {
            $chapters = $values['chapters'];
        }

        try {
            $this->contentService->update(
                intval($values['id']),
                $values[Content::$TITLE],
                $values[Content::$SLUG],
                $values[Content::$STATUS],
                $values[Content::$CONTENT],
                $values[Content::$VIDEO_ID],
                $thumbnail,
                $source,
                $chapters
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
     * @Get("/admin/contents/delete/{id}")
     * @Middleware("admin")
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