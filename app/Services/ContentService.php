<?php

namespace App\Services;

use App\Exceptions\NoFoundException;
use App\Models;

class ContentService
{
    private $content;

    public function __construct(Models\Content $content) {
        $this->content = $content;
    }

    public function getContentForBlog() {
        return $this->content->get();
    }

    public function getContentBySlug(string $slug) {
        $result = $this->content->where('slug', $slug)->get();

        if(!count($result)) {
            throw new NoFoundException("Content not found");
        }

        return $result[0];
    }
}