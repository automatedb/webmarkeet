<?php
/**
 * Created by PhpStorm.
 * User: nickleus
 * Date: 23/02/18
 * Time: 09:49
 */

namespace App\Services;


use App\Exceptions\MaxFileExceededException;
use App\Exceptions\VideoNotFoundException;
use App\Helpers\YoutubeUploader;
use Illuminate\Support\Facades\File;

class YoutubeUploaderService
{
    private $youtubeUploader;

    public function __construct(YoutubeUploader $youtubeUploader) {
        $this->youtubeUploader = $youtubeUploader;
    }

    public function upload(string $filename, string $title, string $description, string $tags = "", string $thumbnail = "") {
        if(!file_exists(storage_path($filename))) {
            throw new VideoNotFoundException('video_not_found');
        }

        if(!empty($thumbnail) && file_exists(storage_path($filename))) {
            $size = File::size(storage_path($thumbnail));

            if($size > 5000000) {
                throw new MaxFileExceededException('max_file_exceeded');
            }
        }

        $tags = $this->convertStringToTag($tags);

        return $this->youtubeUploader->upload($filename, $title, $description, $tags, $thumbnail);
    }

    private function convertStringToTag(string $tags): array {
        if(empty($tags)) {
            return [ ];
        }

        $tags = explode(',', $tags);

        foreach ($tags as $index => $tag) {
            $tags[$index] = trim($tag);
        }

        return $tags;
    }
}