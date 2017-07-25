<?php

namespace Tests\Unit\Services;

use App\Models\Content;
use App\Services\ContentService;
use Tests\TestCase;

class ContentServiceTest extends TestCase
{
    public function testGetContentForBlogNoResultReturn()
    {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $mock->shouldReceive('get')->once()->andReturn([]);

        $contentService = new ContentService($mock);

        // Act
        $results = $contentService->getContentForBlog();

        // Assert
        $this->assertCount(0, $results);
    }

    public function testGetContentForBlogReturn2Result()
    {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $mock->shouldReceive('get')->once()->andReturn([
            [
                'title' => 'A title 1'
            ],
            [
                'title' => 'A title 2'
            ]
        ]);

        $contentService = new ContentService($mock);

        // Act
        $results = $contentService->getContentForBlog();

        // Assert
        $this->assertCount(2, $results);
    }
}
