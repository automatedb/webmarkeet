<?php

namespace Tests\Unit\Services;

use App\Exceptions\ContentNotFoundException;
use App\Exceptions\SlugAlreadyExistsException;
use App\Exceptions\UnexpectedException;
use App\Exceptions\UnknownStatusException;
use App\Jobs\ImageResizer;
use App\Models\Chapter;
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
        $mockChapter = \Mockery::mock(Chapter::class);

        $mock->shouldReceive('where')->twice()->andReturn($mock);
        $mock->shouldReceive('whereNotNull')->once()->andReturn($mock);
        $mock->shouldReceive('orderBy')->once()->andReturn($mock);
        $mock->shouldReceive('get')->once()->andReturn([]);

        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Act
        $results = $contentService->getContentsForBlog();

        // Assert
        $this->assertCount(0, $results);
    }

    public function testGetContentForBlogReturn2Result()
    {
        // Arrange
        $mock = \Mockery::mock(Content::class);
        $mockChapter = \Mockery::mock(Chapter::class);

        $mock->shouldReceive('where')->twice()->andReturn($mock);
        $mock->shouldReceive('whereNotNull')->once()->andReturn($mock);
        $mock->shouldReceive('orderBy')->once()->andReturn($mock);
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

        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Act
        $results = $contentService->getContentsForBlog();

        // Assert
        $this->assertCount(2, $results);
    }

    public function testGetContentWithContentNotFound() {
        // Arrange
        $mock = \Mockery::mock(Content::class);
        $mockChapter = \Mockery::mock(Chapter::class);


        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn(null);

        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Assert
        $this->expectException(ContentNotFoundException::class);

        // Act
        $contentService->getContent(1);

    }

    public function testGetContentWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(Content::class);
        $mockChapter = \Mockery::mock(Chapter::class);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn(new Content([
            'title' => 'title'
        ]));

        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Act
        $result = $contentService->getContent(1);

        // Assert
        $this->assertEquals($result->title, 'title');
    }

    public function testUpdateWithContentNotFound() {
        // Arrange
        $mock = \Mockery::mock(Content::class);
        $mockChapter = \Mockery::mock(Chapter::class);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn(null);


        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Assert
        $this->expectException(ContentNotFoundException::class);

        // Act
        $contentService->update(1, 'title', 'slug', 'DRAFT', 'CONTENT', 'content');
    }

    public function testUpdateWithUnknownStatus() {
        // Arrange
        $mock = \Mockery::mock(Content::class);
        $mockChapter = \Mockery::mock(Chapter::class);


        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Assert
        $this->expectException(UnknownStatusException::class);

        // Act
        $contentService->update(1, 'title', 'slug', 'OTHER', 'CONTENT', 'content');
    }

    public function testUpdateWithFailure() {
        // Arrange
        $mock = \Mockery::mock(Content::class);
        $mockChapter = \Mockery::mock(Chapter::class);

        $userMock = \Mockery::mock(new Content([
            'title' => 'original title'
        ]));

        $userMock->shouldReceive('save')->once()->andReturn(false);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn($userMock);

        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Assert
        $this->expectException(UnexpectedException::class);

        // Act
        $contentService->update(1, 'title', 'slug', 'DRAFT', 'CONTENT', 'content');

    }

    public function testUpdateWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(Content::class);
        $mockChapter = \Mockery::mock(Chapter::class);

        $userMock = \Mockery::mock(new Content([
            'title' => 'original title'
        ]));

        $userMock->shouldReceive('save')->once()->andReturn(true);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn($userMock);

        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Act
        $content = $contentService->update(1, 'title', 'slug', 'DRAFT', 'CONTENT', 'content');

        // Assert
        $this->assertEquals($content->title, 'title');
    }

    public function testDeleteWithContentNotFound() {
        // Arrange
        $mock = \Mockery::mock(Content::class);
        $mockChapter = \Mockery::mock(Chapter::class);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn(null);

        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Assert
        $this->expectException(ContentNotFoundException::class);

        // Act
        $contentService->delete(1);
    }

    public function testDeleteWithFailure() {
        // Arrange
        $mock = \Mockery::mock(Content::class);
        $mockChapter = \Mockery::mock(Chapter::class);

        $contentMock = \Mockery::mock(new Content());

        $contentMock->shouldReceive('delete')->once()->andReturn(false);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn($contentMock);

        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Act
        $result = $contentService->delete(1);

        // Assert
        $this->assertFalse($result);
    }

    public function testDeleteWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(Content::class);
        $mockChapter = \Mockery::mock(Chapter::class);

        $contentMock = \Mockery::mock(new Content());

        $contentMock->shouldReceive('delete')->once()->andReturn(true);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn($contentMock);

        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Act
        $result = $contentService->delete(1);

        // Assert
        $this->assertTrue($result);
    }

    public function testAddWithUnknownStatus() {
        // Arrange
        $mock = \Mockery::mock(Content::class);
        $mockChapter = \Mockery::mock(Chapter::class);

        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Assert
        $this->expectException(UnknownStatusException::class);

        // Act
        $contentService->add(1, 'title', 'slug', 'OTHER', 'CONTENT', 'content', []);

    }

    public function testAddWithSlugAlreadyExists() {
        // Arrange
        $mock = \Mockery::mock(Content::class);
        $mockChapter = \Mockery::mock(Chapter::class);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('count')->once()->andReturn(1);

        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Assert
        $this->expectException(SlugAlreadyExistsException::class);

        // Act
        $contentService->add(1, 'title', 'slug', 'DRAFT', 'CONTENT', 'content', []);

    }

    public function testAddWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(Content::class);
        $mockChapter = \Mockery::mock(Chapter::class);

        $contentMocked = \Mockery::mock(Content::class);

        $contentMocked->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('count')->once()->andReturn(0);
        $mock->shouldReceive('create')->once()->andReturn($contentMocked);

        $contentService = new ContentService($mock, $this->converterMock, $mockChapter);

        // Act
        $result = $contentService->add(1, 'title', 'slug', 'DRAFT', 'CONTENT', 'content', []);

        // Assert
        $this->assertEquals($result, 1);
    }
}
