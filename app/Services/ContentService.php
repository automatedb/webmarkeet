<?php

namespace App\Services;

use App\Exceptions\ContentNotFoundException;
use App\Exceptions\DontMoveFileException;
use App\Exceptions\NoFoundException;
use App\Exceptions\SlugAlreadyExistsException;
use App\Exceptions\UnexpectedException;
use App\Exceptions\UnknownStatusException;
use App\Exceptions\UnknownTypeException;
use App\Jobs\ImageResizer;
use App\Models\Content;
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

    public function getContentForBlog() {
        $contents = $this->content->where('type', Content::CONTENT)
            ->where('status', Content::PUBLISHED)
            ->whereNotNull(Content::$POSTED_AT)
            ->orderBy(Content::$POSTED_AT, 'desc')->get();

        foreach ($contents as $index => $content) {
            $contents[$index]->content = $this->converter->convertToHtml($contents[$index]->content);
        }

        return $contents;
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

    public function update(int $id, string $title, string $slug, string $status, string $type, string $content = null, array $thumbnail = []): Content {
        $this->validParams($status, $type);

        $contentModel = $this->content->where('id', $id)->first();

        if(empty($contentModel)) {
            throw new ContentNotFoundException('Content not found');
        }

        $this->setThumbnail($thumbnail, $contentModel);

        $contentModel->title = $title;
        $contentModel->slug = $slug;
        $contentModel->content = $content;
        $contentModel->status = $status;
        $contentModel->type = $type;

        if(empty($contentModel->posted_at) && $status === Content::PUBLISHED) {
            $contentModel->posted_at = new \DateTime();
        }

        if(!$contentModel->save()) {
            throw new UnexpectedException('Unexpected error');
        }

        $this->dispatch(new ImageResizer($contentModel));

        return $contentModel;
    }

    public function add(int $userId, string $title, string $slug, string $status, string $type, string $content = null, array $thumbnail = []): int {
        $this->validParams($status, $type);

        $n = $this->content->where('slug', $slug)->count();

        if($n) {
            throw new SlugAlreadyExistsException('Slug already exists.');
        }

        $data = [];

        $this->setThumbnail($thumbnail, $data);

        $data[Content::$TITLE] = $title;
        $data[Content::$SLUG] = $slug;
        $data[Content::$CONTENT] = $content;
        $data[Content::$STATUS] = $status;
        $data[Content::$TYPE] = $type;
        $data[Content::$USER_ID] = $userId;

        $content = new Content($data);

        $lastId = 0;

        if($content->save($data)) {
            $lastId = $content->id;

            $this->dispatch(new ImageResizer($content));
        }

        return $lastId;
    }

    public function delete(int $id) {
        /** @var Content $content */
        $content = $this->content->where('id', $id)->first();

        if(empty($content)) {
            throw new ContentNotFoundException('Content not found');
        }

        return $content->delete($id);
    }

    private function setThumbnail($thumbnail, &$data) {
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

    private function validParams(string $status, string $type) {
        if(!array_key_exists($type, config('content.type'))) {
            throw new UnknownTypeException('Type not exists');
        }

        if(!array_key_exists($status, config('content.status'))) {
            throw new UnknownStatusException('Status not exists');
        }
    }

    private function moveThumbnail(array $thumbnail) {
        if(!key_exists(self::HASH_NAME, $thumbnail) || !key_exists(self::ORIGINAL_NAME, $thumbnail)) {
            throw new DontMoveFileException('%s and %s are required to move file.', 100);
        }

        $from = storage_path('app' . DIRECTORY_SEPARATOR . config('content.uploadDirectory') . DIRECTORY_SEPARATOR . $thumbnail[self::HASH_NAME]);
        $to = public_path('img' . DIRECTORY_SEPARATOR . $thumbnail[self::ORIGINAL_NAME]);

        if(!rename($from, $to)) {
            throw new DontMoveFileException("Cannot move file.", 200);
        }
    }
}