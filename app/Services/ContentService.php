<?php

namespace App\Services;

use App\Exceptions\ContentNotFoundException;
use App\Exceptions\DontMoveFileException;
use App\Exceptions\NoFoundException;
use App\Exceptions\SlugAlreadyExistsException;
use App\Exceptions\UnexpectedException;
use App\Exceptions\UnknownStatusException;
use App\Exceptions\UnknownTypeException;
use App\Models\Content;
use League\CommonMark\Converter;

class ContentService
{
    public const HASH_NAME = 'hashName';

    public const ORIGINAL_NAME = 'originalName';

    private $content;

    private $converter;

    public function __construct(Content $content, Converter $converter) {
        $this->content = $content;
        $this->converter = $converter;
    }

    public function getContentForBlog() {
        $contents = $this->content->get();

        foreach ($contents as $index => $content) {
            $contents[$index]->content = $this->converter->convertToHtml($contents[$index]->content);
        }

        return $contents;
    }

    public function getContentBySlug(string $slug) {
        /** @var Content $result */
        $result = $this->content->where('slug', $slug)->first();

        if(empty($result)) {
            throw new NoFoundException("Content not found");
        }

        $result->content = $this->converter->convertToHtml($result->content);

        return $result;
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

    public function update(int $id, string $title, string $slug, string $content, string $status, string $type, array $thumbnail = []): Content {
        if(!array_key_exists($type, config('content.type'))) {
            throw new UnknownTypeException('Type not exists');
        }

        if(!array_key_exists($status, config('content.status'))) {
            throw new UnknownStatusException('Status not exists');
        }

        $contentModel = $this->content->where('id', $id)->first();

        if(empty($contentModel)) {
            throw new ContentNotFoundException('Content not found');
        }

        if(!empty($thumbnail)) {
            try {
                $this->moveThumbnail($thumbnail);

                $contentModel->thumbnail = DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $thumbnail[self::ORIGINAL_NAME];
            } catch (DontMoveFileException $e) {
                if($e->getCode() === 200) {
                    throw new UnexpectedException("The update method isn't used correctly. A right thumbnail parameter is required.");
                }
            }
        }

        $contentModel->title = $title;
        $contentModel->slug = $slug;
        $contentModel->content = $content;
        $contentModel->status = $status;
        $contentModel->type = $type;

        if(!$contentModel->save()) {
            throw new UnexpectedException('Unexpected error');
        }

        return $contentModel;
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