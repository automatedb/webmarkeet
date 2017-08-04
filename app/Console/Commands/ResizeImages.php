<?php

namespace App\Console\Commands;

use App\Helpers\ImageResizer;
use App\Models\Content;
use Illuminate\Console\Command;

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
                $imageResizer = new ImageResizer($content);
                $imageResizer->resize();
            }
        }
    }
}
