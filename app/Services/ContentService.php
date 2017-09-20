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
use App\Jobs\SiteMapGenerator;
use App\Models\Chapter;
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

    private $chapter;

    public function __construct(Content $content, Converter $converter, Chapter $chapter) {
        $this->content = $content;
        $this->converter = $converter;
        $this->chapter = $chapter;
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

    public function getContentsForFormations() {
        return $this->getContentsForType(Content::FORMATION);
    }

    public function getContentBySlug(string $slug) {
        /** @var Content $content */
        $content = $this->content->where(Content::$SLUG, $slug)->first();

        if(empty($content)) {
            throw new NoFoundException("Content not found");
        }

        $content->content = $this->converter->convertToHtml($content->content);

        return $content;
    }

    public function getSourceFromChapter(int $chapterId, string $fileType) {
        $source = $this->chapter->where('id', $chapterId)
            ->first()
            ->sources()
            ->where(Source::TYPE, $fileType)
            ->first();

        if(empty($source)) {
            throw new NoFoundException("Chapter not found");
        }

        return $source;
    }

    public function getContents() {
        return $this->content
            ->orderBy(Content::$POSTED_AT, 'desc')
            ->get();
    }

    public function getContent($id): Content {
        $content = $this->content->where('id', $id)->first();

        if(empty($content)) {
            throw new ContentNotFoundException('Content not found');
        }

        return $content;
    }

    public function update(int $id, string $title, string $slug, string $status, string $content = null, string $videoId = null, array $thumbnail = [], array $sources = [], array $chapters = []): Content {
        $type = empty($videoId) ? Content::CONTENT : Content::TUTORIAL;

        $type = empty($chapters) ? $type : Content::FORMATION;

        $this->validParams($status, $type);

        $contentModel = $this->content->where('id', $id)->first();

        if(empty($contentModel)) {
            throw new ContentNotFoundException('Content not found');
        }

        $sourceModel = $contentModel->sources()->first();

        $this->setThumbnail($thumbnail, $contentModel);

        if(empty($sourceModel)) {
            $sourceModel = [];
        }

        $this->setSources($sources, $sourceModel);

        $contentModel[Content::$TITLE] = $title;
        $contentModel[Content::$SLUG] = $slug;
        $contentModel[Content::$CONTENT] = $content;
        $contentModel[Content::$STATUS] = $status;
        $contentModel[Content::$TYPE] = $type;
        $contentModel[Content::$VIDEO_ID] = $videoId;

        $this->publishContent($status, $contentModel);

        if(!$contentModel->save()) {
            throw new UnexpectedException('Unexpected error');
        }

        if(!empty($sourceModel) && !empty($sourceModel->id)) {
            $sourceModel->save();
        } else if(!empty($sources)) {
            $contentModel->sources()->create($sourceModel);
        }

        $this->updateChapters($chapters, $contentModel);

        if($contentModel[Content::$STATUS] == Content::PUBLISHED) {
            $this->dispatch(new SiteMapGenerator($this));
        }

        if(!empty($thumbnail)) {
            $this->dispatch(new ImageResizer($contentModel));
        }

        return $contentModel;
    }

    public function add(int $userId, string $title, string $slug, string $status, string $content = null, string $videoId = null, array $thumbnail = [], array $sources = [], array $chapters = []): int {
        $type = empty($videoId) ? Content::CONTENT : Content::TUTORIAL;

        $type = empty($chapters) ? $type : Content::FORMATION;

        $this->validParams($status, $type);

        $n = $this->content->where('slug', $slug)->count();

        if($n) {
            throw new SlugAlreadyExistsException('Slug already exists.');
        }

        $contentModel = [];

        $this->setThumbnail($thumbnail, $contentModel);

        if(!empty($sources)) {
            $sourceModel = [];
            $this->setSources($sources, $sourceModel);
        }

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

            if(!empty($sources)) {
                $content->sources()->create($sourceModel);
            }

            $lastId = $content->id;

            $this->updateChapters($chapters, $content);

            if($contentModel[Content::$STATUS] == Content::PUBLISHED) {
                $this->dispatch(new SiteMapGenerator($this));
            }

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

    private function updateChapters(array $chapters, Content $contentModel) {
        if(!empty($chapters)) {
            foreach ($chapters as $chapter) {
                $chapterModel = $this->chapter->where('id', $chapter['id'])->first();

                if(empty($chapterModel)) {
                    $this->createChapter($chapter, $contentModel);
                    continue;
                }

                $chapterModel[Chapter::TITLE] = $chapter[Chapter::TITLE];
                $chapterModel[Chapter::CONTENT] = $chapter[Chapter::CONTENT];

                if(!$chapterModel->save()) {
                    throw new UnexpectedException('Unexpected error');
                }

                if(!empty($chapter[config('sources.type.VIDEO')])) {
                    $this->setChaptersSources($chapterModel, config('sources.type.VIDEO'), $chapter);
                }

                if(!empty($chapter[config('sources.type.FILE')])) {
                    $this->setChaptersSources($chapterModel, config('sources.type.FILE'), $chapter);
                }
            }
        }
    }

    private function createChapter(array $chapter, Content $contentModel) {
        $chapterModel = [
            Chapter::TITLE => $chapter[Chapter::TITLE],
            Chapter::CONTENT => $chapter[Chapter::CONTENT]
        ];

        if(!$chapterModel = $contentModel->chapters()->create($chapterModel)) {
            throw new UnexpectedException('Unexpected error');
        }

        if(!empty($chapter[config('sources.type.VIDEO')])) {
            $this->setChaptersSources($chapterModel, config('sources.type.VIDEO'), $chapter);
        }

        if(!empty($chapter[config('sources.type.FILE')])) {
            $this->setChaptersSources($chapterModel, config('sources.type.FILE'), $chapter);
        }
    }

    private function setChaptersSources(Chapter $chapterModel, string $type, array $chapter) {
        $sourceModel = $chapterModel->sources()->where(Source::TYPE, $type)->first();

        $chapter[$type]->store(config('content.uploadDirectory'));

        $video[self::HASH_NAME] = $chapter[$type]->hashName();
        $video[self::ORIGINAL_NAME] = $chapter[$type]->getClientOriginalName();

        if(empty($sourceModel)) {
            $sourceModel = [];
            $sourceModel[Source::TYPE] = $type;
        }

        $sourceModel[Source::NAME] = $this->formatName($video);

        $from = storage_path('app' . DIRECTORY_SEPARATOR . config('content.uploadDirectory') . DIRECTORY_SEPARATOR . $video[self::HASH_NAME]);
        $to = storage_path('app' . DIRECTORY_SEPARATOR . 'public'  . DIRECTORY_SEPARATOR . $video[self::ORIGINAL_NAME]);

        if(!rename($from, $to)) {
            throw new DontMoveFileException("Cannot move file.", 200);
        }

        if(!empty($sourceModel) && !empty($sourceModel->id)) {
            $sourceModel->save();
        } else if(!empty($sourceModel)) {
            $chapterModel->sources()->create($sourceModel);
        }
    }

    private function getContentsForType(string $type) {
        $contents = $this->content->where(Content::$TYPE, $type)
            ->where(Content::$STATUS, Content::PUBLISHED)
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