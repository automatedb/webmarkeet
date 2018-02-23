<?php
/**
 * Created by PhpStorm.
 * User: nickleus
 * Date: 23/02/18
 * Time: 10:24
 */

namespace App\Helpers;


use Dawson\Youtube\Facades\Youtube;

class YoutubeUploader
{
    public function upload(string $filename, string $title, string $description, array $tags = [], string $thumbnail = ""): string {
        $video = Youtube::upload(storage_path($filename), [
            'title'       => $title,
            'description' => $description,
            'tags'	      => $tags,
            'category_id' => 27
        ]);

        return $video->getVideoId();
    }
}