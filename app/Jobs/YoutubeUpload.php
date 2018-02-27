<?php

namespace App\Jobs;

use App\Exceptions\MaxFileExceededException;
use App\Exceptions\VideoNotFoundException;
use App\Helpers\YoutubeUploader;
use App\Models\Content;
use App\Services\YoutubeUploaderService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class YoutubeUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id;
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
    public function __construct(string $id, string $filename, string $title, string $description, string $tags, string $thumbnail)
    {
        $this->id = $id;
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
        $youtubeUploaderService = new YoutubeUploaderService(new YoutubeUploader());

        try {
            Log::info(sprintf('Upload in progress for the file: %s.', '/app/uploads/' . $this->filename));
            $video_id = $youtubeUploaderService->upload('/app/uploads/' . $this->filename, $this->title, $this->description, $this->tags, $this->thumbnail);

            Content::where('id', $this->id)->update([
                Content::$VIDEO_ID => $video_id,
                Content::$TYPE => Content::TUTORIAL
            ]);

            Log::info(sprintf('Video %s uploaded and linked with the content id: %s.', $video_id, $this->id));
        } catch (VideoNotFoundException | MaxFileExceededException $e) {
            Log::error($e->getMessage());
        }
    }
}
