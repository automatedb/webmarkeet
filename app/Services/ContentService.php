<?php

namespace App\Services;

use App\Exceptions\ContentNotFoundException;
use App\Exceptions\DontMoveFileException;
use App\Exceptions\NoFoundException;
use App\Exceptions\SlugAlreadyExistsException;
use App\Exceptions\UnexpectedException;
use App\Exceptions\UnknownStatusException;
use App\Exceptions\UnknownTypeException;
use App\Jobs\ImageCleaner;
use App\Jobs\ImageResizer;
use App\Models\Content;
use App\Models\Source;
use Illuminate\Foundation\Bus\DispatchesJobs;
use League\CommonMark\Converter;

class ContentService
{
    use DispatchesJobs;

    public const HASH_NAME = 'hashName';

    public const ORIGINAL_NAME = 'originalName';

    private $content;

    private $converter;

    public function __construct(Content $content, Converter $converter) {
        $this->content = $content;
        $this->converter = $converter;
    }

    public function getRecentContentsForBlog() {
        $contents = $this->content->where('type', Content::CONTENT)
            ->where('status', Content::PUBLISHED)
            ->whereNotNull(Content::$POSTED_AT)
            ->orderBy(Content::$POSTED_AT, 'desc')->limit(3)->get();

        foreach ($contents as $index => $content) {
            $contents[$index]->content = $this->converter->convertToHtml($contents[$index]->content);
        }

        return $contents;
    }

    public function getRecentContentsForTutorial() {
        $contents = $this->content->where('type', Content::TUTORIAL)
            ->where('status', Content::PUBLISHED)
            ->whereNotNull(Content::$POSTED_AT)
            ->orderBy(Content::$POSTED_AT, 'desc')->limit(3)->get();

        foreach ($contents as $index => $content) {
            $contents[$index]->content = $this->converter->convertToHtml($contents[$index]->content);
        }

        return $contents;
    }

    public function getContentsForBlog() {
        return $this->getContentsForType(Content::CONTENT);
    }

    public function getContentsForTutorials() {
        return $this->getContentsForType(Content::TUTORIAL);
    }

    public function getContentBySlug(string $slug) {
        /** @var Content $content */
        $content = $this->content->where('slug', $slug)->first();

        if(empty($content)) {
            throw new NoFoundException("Content not found");
        }

        $content->content = $this->converter->convertToHtml($content->content);

        return $content;
    }

    public function getContents() {
        return $this->content->get();
    }

    public function getContent($id): Content {
        $content = $this->content->where('id', $id)->first();

        if(empty($content)) {
            throw new ContentNotFoundException('Content not found');
        }

        return $content;
    }

    public function update(int $id, string $title, string $slug, string $status, string $content = null, string $videoId = null, array $thumbnail = [], array $sources = []): Content {
        $type = empty($videoId) ? Content::CONTENT : Content::TUTORIAL;

        $this->validParams($status, $type);

        $contentModel = $this->content->where('id', $id)->first();
        $sourceModel = $contentModel->sources()->first();

        if(empty($contentModel)) {
            throw new ContentNotFoundException('Content not found');
        }

        $this->setThumbnail($thumbnail, $contentModel);

        if(empty($sourceModel)) {
            $sourceModel = new Content();
        }

        $this->setSources($sources, $sourceModel);

        $contentModel->title = $title;
        $contentModel->slug = $slug;
        $contentModel->content = $content;
        $contentModel->status = $status;
        $contentModel->type = $type;
        $contentModel->video_id = $videoId;

        $this->publishContent($status, $contentModel);

        if(!$contentModel->save()) {
            throw new UnexpectedException('Unexpected error');
        }

        if(!empty($sourceModel)) {
            $sourceModel->save();
        }

        if(!empty($thumbnail)) {
            $this->dispatch(new ImageResizer($contentModel));
        }

        return $contentModel;
    }

    public function add(int $userId, string $title, string $slug, string $status, string $content = null, string $videoId = null, array $thumbnail = [], array $sources = []): int {
        $type = empty($videoId) ? Content::CONTENT : Content::TUTORIAL;

        $this->validParams($status, $type);

        $n = $this->content->where('slug', $slug)->count();

        if($n) {
            throw new SlugAlreadyExistsException('Slug already exists.');
        }

        $contentModel = [];
        $sourceModel = [];

        $this->setThumbnail($thumbnail, $contentModel);
        $this->setSources($sources, $sourceModel);

        $contentModel[Content::$TITLE] = $title;
        $contentModel[Content::$SLUG] = $slug;
        $contentModel[Content::$CONTENT] = $content;
        $contentModel[Content::$STATUS] = $status;
        $contentModel[Content::$TYPE] = $type;
        $contentModel[Content::$USER_ID] = $userId;
        $contentModel[Content::$VIDEO_ID] = $videoId;

        $this->publishContent($status, $contentModel);

        $lastId = 0;

        if($content = $this->content->create($contentModel)) {
            $content->sources()->create($sourceModel);

            $lastId = $content->id;

            if(!empty($thumbnail)) {
                $this->dispatch(new ImageResizer($content));
            }
        }

        return $lastId;
    }

    public function delete(int $id): bool {
        /** @var Content $content */
        $content = $this->content->where('id', $id)->first();

        if(empty($content)) {
            throw new ContentNotFoundException('Content not found');
        }

        $this->dispatch(new ImageCleaner($id));

        return $content->delete($id);
    }

    private function getContentsForType(string $type) {
        $contents = $this->content->where('type', $type)
            ->where('status', Content::PUBLISHED)
            ->whereNotNull(Content::$POSTED_AT)
            ->orderBy(Content::$POSTED_AT, 'desc')->get();

        foreach ($contents as $index => $content) {
            $contents[$index]->content = $this->converter->convertToHtml($contents[$index]->content);
        }

        return $contents;
    }

    private function setThumbnail(array $thumbnail, &$data): void {
        if(!empty($thumbnail)) {
            try {
                $this->moveThumbnail($thumbnail);

                $data[Content::$THUMBNAIL] = $thumbnail[self::ORIGINAL_NAME];
            } catch (DontMoveFileException $e) {
                if($e->getCode() === 200) {
                    throw new UnexpectedException("The update method isn't used correctly. A right thumbnail parameter is required.");
                }
            }
        }
    }

    private function setSources(array &$sources, &$data): void {
        if(!empty($sources)) {
            $sources[self::ORIGINAL_NAME] = $this->formatName($sources);

            $from = storage_path('app' . DIRECTORY_SEPARATOR . config('content.uploadDirectory') . DIRECTORY_SEPARATOR . $sources[self::HASH_NAME]);
            $to = storage_path('app' . DIRECTORY_SEPARATOR . 'public'  . DIRECTORY_SEPARATOR . $sources[self::ORIGINAL_NAME]);

            $data[Source::NAME] = $sources[self::ORIGINAL_NAME];

            if(!rename($from, $to)) {
                throw new DontMoveFileException("Cannot move file.", 200);
            }
        }
    }

    private function validParams(string $status, string $type) {
        if(!array_key_exists($type, config('content.type'))) {
            throw new UnknownTypeException('Type not exists');
        }

        if(!array_key_exists($status, config('content.status'))) {
            throw new UnknownStatusException('Status not exists');
        }
    }

    private function moveThumbnail(array &$thumbnail) {
        if(!key_exists(self::HASH_NAME, $thumbnail) || !key_exists(self::ORIGINAL_NAME, $thumbnail)) {
            throw new DontMoveFileException('%s and %s are required to move file.', 100);
        }

        $thumbnail[self::ORIGINAL_NAME] = $this->formatName($thumbnail);

        $from = storage_path('app' . DIRECTORY_SEPARATOR . config('content.uploadDirectory') . DIRECTORY_SEPARATOR . $thumbnail[self::HASH_NAME]);
        $to = public_path('img' . DIRECTORY_SEPARATOR . $thumbnail[self::ORIGINAL_NAME]);

        if(!rename($from, $to)) {
            throw new DontMoveFileException("Cannot move file.", 200);
        }
    }

    private function formatName(array $data): string {
        $originalNameExploded = explode('.', $data[self::ORIGINAL_NAME]);

        $ext = array_pop($originalNameExploded);
        $originalName = strtolower(str_slug(implode('.', $originalNameExploded)));

        return $originalName . '.' . $ext;
    }

    private function publishContent(string $status, &$contentModel): void
    {
        if (empty($contentModel[Content::$POSTED_AT]) && $status == Content::PUBLISHED) {
            $contentModel[Content::$POSTED_AT] = new \DateTime();
        }
    }
}