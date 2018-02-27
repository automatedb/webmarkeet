<?php

namespace Tests\Feature\Controller\Api;

use App\Jobs\YoutubeUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadControllerTest extends TestCase
{
    /**
     * Test qu'il ne soit possible d'envoyer formulaire incomplet
     */
    public function testIndex_FileRequiredCase_ExpectException() {
        // Arrange
        Queue::fake();

        // Act
        $response = $this->json('POST', '/api/v1/upload/youtube', [
            'id' => "1",
            'video' => ''
        ]);

        // Assert
        Queue::assertNotPushed(YoutubeUpload::class);

        $response->assertJson([
            'message' => 'unexpected_error'
        ]);
    }

    /**
     * Test qu'il ne soit possible d'envoyer formulaire incomplet
     */
    public function testIndex_TitleRequiredCase_ExpectException() {
        // Arrange
        // Act
        $response = $this->json('POST', '/api/v1/upload/youtube', [
            'id' => "1",
            'video' => UploadedFile::fake()->create('video.mp4', 1024)
        ]);

        // Assert
        $response->assertJson([
            'message' => 'unexpected_error'
        ]);
    }

    /**
     * Test que le fichier vidéo ait bien été enregistré sur le serveur
     */
    public function testIndex_FileExistsCase_Success() {
        // Arrange
        Storage::fake(config('content.uploadDirectory'));

        // Act
        $response = $this->json('POST', '/api/v1/upload/youtube', [
            'id' => "1",
            'video' => $file = UploadedFile::fake()->create('video.mp4', 1024),
            'title' => "Cool title"
        ]);

        // Assert
        $this->assertFileExists(storage_path('app/uploads/' . $file->hashName()));

        unlink(storage_path('app/uploads/' . $file->hashName()));

        $response->assertJson([
            'message' => 'success'
        ]);
    }

    /**
     * Test que le gestionnaire de queue a bien été lancé
     */
    public function testIndex_QueueManagerCase_Success() {
        // Arrange
        Queue::fake();
        Storage::fake(config('content.uploadDirectory'));

        $values = [
            'id' => "1",
            'video' => $file = UploadedFile::fake()->create('video.mp4', 1024),
            'thumbnail' => $thumbnail = UploadedFile::fake()->create('thumbnail.jpg', 1024),
            'title' => "Cool title",
            'description' => "Cool description"
        ];

        // Act
        $response = $this->json('POST', '/api/v1/upload/youtube', $values);

        // Assert
        $this->assertFileExists(storage_path('app/uploads/' . $file->hashName()));

        Queue::assertPushed(YoutubeUpload::class, function ($job) use ($values, $file, $thumbnail) {
            return $job->filename === $file->hashName()
                && $job->id === $values['id']
                && $job->title === $values['title']
                && $job->description === $values['description']
                && $job->tags === ""
                && $job->thumbnail === $thumbnail->hashName();
        });

        unlink(storage_path('app/uploads/' . $file->hashName()));

        $response->assertJson([
            'message' => 'success'
        ]);
    }

    /**
     * Test que le gestionnaire de queue a bien été lancé sans thumbnail
     */
    public function testIndex_QueueManagerWithoutThumbnailCase_Success() {
        // Arrange
        Queue::fake();
        Storage::fake(config('content.uploadDirectory'));

        $values = [
            'id' => "1",
            'video' => $file = UploadedFile::fake()->create('video.mp4', 1024),
            'title' => "Cool title",
            'description' => "Cool description"
        ];

        // Act
        $response = $this->json('POST', '/api/v1/upload/youtube', $values);

        // Assert
        $this->assertFileExists(storage_path('app/uploads/' . $file->hashName()));

        Queue::assertPushed(YoutubeUpload::class, function ($job) use ($values, $file) {
            return $job->filename === $file->hashName()
                && $job->id === $values['id']
                && $job->title === $values['title']
                && $job->description === $values['description']
                && $job->tags === "";
        });

        unlink(storage_path('app/uploads/' . $file->hashName()));

        $response->assertJson([
            'message' => 'success'
        ]);
    }

    /**
     * Test que le gestionnaire de queue a bien été lancé sans id de contenu
     */
    public function testIndex_WithoutIdCase_Success() {
        // Arrange
        Queue::fake();
        Storage::fake(config('content.uploadDirectory'));

        $values = [
            'video' => $file = UploadedFile::fake()->create('video.mp4', 1024),
            'title' => "Cool title",
            'description' => "Cool description"
        ];

        // Act
        $response = $this->json('POST', '/api/v1/upload/youtube', $values);

        // Assert
        Queue::assertNotPushed(YoutubeUpload::class);

        $response->assertJson([
            'message' => 'unexpected_error'
        ]);
    }
}
