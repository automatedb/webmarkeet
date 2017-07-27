<?php

namespace Tests\Unit\Services;

use App\Exceptions\ContentNotFoundException;
use App\Exceptions\SlugAlreadyExistsException;
use App\Exceptions\UnexpectedException;
use App\Exceptions\UnknownStatusException;
use App\Exceptions\UnknownTypeException;
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

    public function testGetContentWithContentNotFound() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn(null);

        $contentService = new ContentService($mock);

        // Assert
        $this->expectException(ContentNotFoundException::class);

        // Act
        $contentService->getContent(1);

    }

    public function testGetContentWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn(new Content([
            'title' => 'title'
        ]));

        $contentService = new ContentService($mock);

        // Act
        $result = $contentService->getContent(1);

        // Assert
        $this->assertEquals($result->title, 'title');
    }

    public function testUpdateWithContentNotFound() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $mock->shouldReceive('where')->twice()->andReturn($mock);
        $mock->shouldReceive('get')->once()->andReturn([
            new Content()
        ]);
        $mock->shouldReceive('first')->once()->andReturn(null);

        $contentService = new ContentService($mock);

        // Assert
        $this->expectException(ContentNotFoundException::class);

        // Act
        $contentService->update(1, 'title', 'slug', 'content', 'DRAFT', 'CONTENT', null);
    }

    public function testUpdateWithUnknownType() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $contentService = new ContentService($mock);

        // Assert
        $this->expectException(UnknownTypeException::class);

        // Act
        $contentService->update(1, 'title', 'slug', 'content', 'DRAFT', 'OTHER', null);
    }

    public function testUpdateWithUnknownStatus() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $contentService = new ContentService($mock);

        // Assert
        $this->expectException(UnknownStatusException::class);

        // Act
        $contentService->update(1, 'title', 'slug', 'content', 'OTHER', 'CONTENT', null);
    }

    public function testUpdateWithFailure() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $userMock = \Mockery::mock(new Content([
            'title' => 'original title'
        ]));

        $userMock->shouldReceive('save')->once()->andReturn(false);

        $mock->shouldReceive('where')->twice()->andReturn($mock);
        $mock->shouldReceive('get')->once()->andReturn([
            new Content()
        ]);
        $mock->shouldReceive('first')->once()->andReturn($userMock);

        $contentService = new ContentService($mock);

        // Assert
        $this->expectException(UnexpectedException::class);

        // Act
        $contentService->update(1, 'title', 'slug', 'content', 'DRAFT', 'CONTENT', null);

    }

    public function testUpdateWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $userMock = \Mockery::mock(new Content([
            'title' => 'original title'
        ]));

        $userMock->shouldReceive('save')->once()->andReturn(true);

        $mock->shouldReceive('where')->twice()->andReturn($mock);
        $mock->shouldReceive('get')->once()->andReturn([
            new Content()
        ]);
        $mock->shouldReceive('first')->once()->andReturn($userMock);

        $contentService = new ContentService($mock);

        // Act
        $content = $contentService->update(1, 'title', 'slug', 'content', 'DRAFT', 'CONTENT', null);

        // Assert
        $this->assertEquals($content->title, 'title');
    }
}
