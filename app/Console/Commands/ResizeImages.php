<?php

namespace App\Console\Commands;

use App\Models\Content;
use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;

class ResizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:resize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permet de redimensionner les imahes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $contents = Content::get();

        foreach ($contents as $content) {
            var_dump(public_path('img/' . $content->thumbnail));

            if(is_file(public_path('img/' . $content->thumbnail))) {
                $this->makeDirectory($content->id);

                $this->imageResizer($content->id, $content->thumbnail);
            }
        }
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
}
