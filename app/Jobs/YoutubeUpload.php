<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class YoutubeUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filename;
    public $title;
    public $description;
    public $tags;
    public $thumbnail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $filename, string $title, string $description, string $tags, string $thumbnail)
    {
        $this->filename = $filename;
        $this->title = $title;
        $this->description = $description;
        $this->tags = $tags;
        $this->thumbnail = $thumbnail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
