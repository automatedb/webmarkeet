<?php
namespace App\Helpers;


use App\Models\Content;
use Intervention\Image\Facades\Image;

class ImageResizer
{
    /** @var Content */
    private $content;

    public function __construct(Content $content)
    {
        $this->content = $content;
    }

    public function resize()
    {
        $this->makeDirectory($this->content->id);

        $this->imageResizer($this->content->id, $this->content->thumbnail);
    }

    private function makeDirectory(int $id) {
        if (!file_exists(public_path('img/posts/' . $id))) {
            mkdir(public_path('img/posts/' . $id), 0755, true);
        }
    }

    private function imageResizer(int $id, string $imageName): void {
        $fileExploded = explode('.', $imageName);

        $ext = array_pop($fileExploded);
        $basename = implode('.', $fileExploded);

        $this->resizeForPostList($id, $imageName, $basename, $ext);
        $this->resizeForPost($id, $imageName, $basename, $ext);
        $this->resizeForOgImage($id, $imageName, $basename, $ext);
        $this->resizeForLastTutorial($id, $imageName, $basename, $ext);
        $this->resizeForHomePage
        ($id, $imageName, $basename, $ext);
    }

    private function resizeForPostList(int $id, string $imageName, string $basename, string $ext): void {
        $img = Image::make(public_path('img/' . $imageName));

        $width = $img->width();

        $img->crop($width, 500)->resize(730, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('img/posts/' . ($id) . '/' . $basename . '-730' . '.' . $ext));
    }

    private function resizeForPost(int $id, string $imageName, string $basename, string $ext): void {
        $img = Image::make(public_path('img/' . $imageName));

        $width = $img->width();

        $img->crop($width, 300)->save(public_path('img/posts/' . ($id) . '/' . $basename . '-300' . '.' . $ext));
    }

    private function resizeForOgImage(int $id, string $imageName, string $basename, string $ext): void {
        $img = Image::make(public_path('img/' . $imageName));

        $img->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('img/posts/' . ($id) . '/' . $basename . '-1200' . '.' . $ext));
    }

    private function resizeForLastTutorial(int $id, string $imageName, string $basename, string $ext): void {
        $img = Image::make(public_path('img/' . $imageName));

        $img->resize(540, null, function ($constraint) {
            $constraint->aspectRatio();
        })->crop(540, 170)->save(public_path('img/posts/' . ($id) . '/' . $basename . '-540' . '.' . $ext));
    }

    private function resizeForHomePage(int $id, string $imageName, string $basename, string $ext): void {
        $img = Image::make(public_path('img/' . $imageName));

        $img->crop(356, 200)->save(public_path('img/posts/' . ($id) . '/' . $basename . '-356' . '.' . $ext));
    }
}