<?php

namespace App\Helpers;

class ImgHelper
{
    public static function link($thumbnail, $id, $size) {
        $fileExploded = explode('.', $thumbnail);

        $fileExt = array_pop($fileExploded);
        $fileName = implode('.', $fileExploded);

        $file = sprintf('%s-%s.%s', $fileName, $size, $fileExt);

        $arrayPath = [
            '/img/posts',
            $id,
            $file
        ];

        return implode('/', $arrayPath);
    }
}