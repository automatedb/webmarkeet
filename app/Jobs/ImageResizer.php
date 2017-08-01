<?php

namespace App\Jobs;

use App\Models\Content;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ImageResizer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Content
     */
    private $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Content $content)
    {
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $id = $this->content->id;

        $fileExploded = explode('.', $this->content->thumbnail);

        $ext = array_pop($fileExploded);
        $basename = implode('.', $fileExploded);

        $this->makeDirectory($id);

        $this->resizeForPostList($id, $this->content->thumbnail, $basename, $ext);
        $this->resizeForPost($id, $this->content->thumbnail, $basename, $ext);
        $this->resizeForOgImage($id, $this->content->thumbnail, $basename, $ext);
    }

    private function makeDirectory(int $id): void
    {
        if (!file_exists(public_path('img/posts/' . $id))) {
            File::makeDirectory(public_path('img/posts/' . $id), 0777, true, true);
        }
    }

    private function resizeForPostList(int $id, string $imageName, string $basename, string $ext): void
    {
        Log::info("Starting resize for post list...");

        $img = Image::make(public_path('img/' . $imageName));

        $width = $img->width();

        $img->crop($width, 500)->resize(730, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('img/posts/' . ($id) . '/' . $basename . '-730' . '.' . $ext));

        Log::info("Resize for post list finished");
    }

    private function resizeForPost(int $id, string $imageName, string $basename, string $ext): void
    {
        Log::info("Starting resize for post...");

        $img = Image::make(public_path('img/' . $imageName));

        $width = $img->width();

        $img->crop($width, 300)->save(public_path('img/posts/' . ($id) . '/' . $basename . '-300' . '.' . $ext));

        Log::info("Resize for post finished");
    }

    private function resizeForOgImage(int $id, string $imageName, string $basename, string $ext): void
    {
        Log::info("Starting resize for SMO...");

        $img = Image::make(public_path('img/' . $imageName));

        $img->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('img/posts/' . ($id) . '/' . $basename . '-1200' . '.' . $ext));

        Log::info("Resize for SMO finished");
    }
}
