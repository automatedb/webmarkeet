<?php

namespace Tests\Unit\Services;

use App\Exceptions\ContentNotFoundException;
use App\Exceptions\SlugAlreadyExistsException;
use App\Exceptions\UnexpectedException;
use App\Exceptions\UnknownStatusException;
use App\Exceptions\UnknownTypeException;
use App\Models\Content;
use App\Services\ContentService;
use League\CommonMark\Converter;
use Tests\TestCase;

class ContentServiceTest extends TestCase
{
    private $converterMock;

    public function setUp()
    {
        parent::setUp();

        $this->converterMock = \Mockery::mock(Converter::class);
    }

    public function testGetContentForBlogNoResultReturn()
    {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $mock->shouldReceive('get')->once()->andReturn([]);

        $contentService = new ContentService($mock, $this->converterMock);

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
            new Content([
                'title' => 'A title 1',
                'content' => 'content'
            ]),
            new Content([
                'title' => 'A title 2',
                'content' => 'content'
            ])
        ]);

        $this->converterMock->shouldReceive('convertToHtml')->twice()->andReturn('string');

        $contentService = new ContentService($mock, $this->converterMock);

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

        $contentService = new ContentService($mock, $this->converterMock);

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

        $contentService = new ContentService($mock, $this->converterMock);

        // Act
        $result = $contentService->getContent(1);

        // Assert
        $this->assertEquals($result->title, 'title');
    }

    public function testUpdateWithContentNotFound() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn(null);

        $contentService = new ContentService($mock, $this->converterMock);

        // Assert
        $this->expectException(ContentNotFoundException::class);

        // Act
        $contentService->update(1, 'title', 'slug', 'content', 'DRAFT', 'CONTENT');
    }

    public function testUpdateWithUnknownType() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $contentService = new ContentService($mock, $this->converterMock);

        // Assert
        $this->expectException(UnknownTypeException::class);

        // Act
        $contentService->update(1, 'title', 'slug', 'content', 'DRAFT', 'OTHER');
    }

    public function testUpdateWithUnknownStatus() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $contentService = new ContentService($mock, $this->converterMock);

        // Assert
        $this->expectException(UnknownStatusException::class);

        // Act
        $contentService->update(1, 'title', 'slug', 'content', 'OTHER', 'CONTENT');
    }

    public function testUpdateWithFailure() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $userMock = \Mockery::mock(new Content([
            'title' => 'original title'
        ]));

        $userMock->shouldReceive('save')->once()->andReturn(false);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn($userMock);

        $contentService = new ContentService($mock, $this->converterMock);

        // Assert
        $this->expectException(UnexpectedException::class);

        // Act
        $contentService->update(1, 'title', 'slug', 'content', 'DRAFT', 'CONTENT');

    }

    public function testUpdateWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $userMock = \Mockery::mock(new Content([
            'title' => 'original title'
        ]));

        $userMock->shouldReceive('save')->once()->andReturn(true);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn($userMock);

        $contentService = new ContentService($mock, $this->converterMock);

        // Act
        $content = $contentService->update(1, 'title', 'slug', 'content', 'DRAFT', 'CONTENT');

        // Assert
        $this->assertEquals($content->title, 'title');
    }

    public function testDeleteWithContentNotFound() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn(null);

        $contentService = new ContentService($mock, $this->converterMock);

        // Assert
        $this->expectException(ContentNotFoundException::class);

        // Act
        $contentService->delete(1);
    }

    public function testDeleteWithFailure() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $contentMock = \Mockery::mock(new Content());

        $contentMock->shouldReceive('delete')->once()->andReturn(false);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn($contentMock);

        $contentService = new ContentService($mock, $this->converterMock);

        // Act
        $result = $contentService->delete(1);

        // Assert
        $this->assertFalse($result);
    }

    public function testDeleteWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(Content::class);

        $contentMock = \Mockery::mock(new Content());

        $contentMock->shouldReceive('delete')->once()->andReturn(true);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn($contentMock);

        $contentService = new ContentService($mock, $this->converterMock);

        // Act
        $result = $contentService->delete(1);

        // Assert
        $this->assertTrue($result);
    }
}
