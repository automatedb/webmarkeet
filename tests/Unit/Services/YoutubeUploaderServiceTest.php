<?php

namespace Tests\Unit\Services;

use App\Exceptions\ContentNotFoundException;
use App\Exceptions\MaxFileExceededException;
use App\Exceptions\VideoNotFoundException;
use App\Helpers\YoutubeUploader;
use App\Models\Content;
use App\Services\YoutubeUploaderService;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class YoutubeUploaderServiceTest extends TestCase
{
    private $pathFile = "app/uploads/intro.mp4";
    private $title = "";
    private $description = "";
    private $thumbnail = "app/uploads/thumbnail.png";

    /**
     * Génère une erreur si le fichier n'existe pas
     */
    public function testUpload_NotFoundFileCase_ExpectException() {
        // Arrange
        $videoUploaderMock = \Mockery::mock(YoutubeUploader::class);

        $youtubeUploaderService = new YoutubeUploaderService($videoUploaderMock);

        // Assert
        $this->expectException(VideoNotFoundException::class);

        // Act
        $youtubeUploaderService->upload("uploads/not-found-video.mp4", "", "");

    }

    /**
     * Vérifie que les tags soient bien envoyé sous forme de tableau sur la base
     * d'une chaîne de caractères
     */
    public function testUpload_TagCase_ConvertStringToArray() {
        // Arrange
        $videoUploaderMock = \Mockery::mock(YoutubeUploader::class);

        // Assert
        $videoUploaderMock->shouldReceive('upload')->once()
            ->with($this->pathFile, $this->title, $this->description, [
                'tag 1',
                'tag 2',
                'tag 3'
            ], "");

        $youtubeUploaderService = new YoutubeUploaderService($videoUploaderMock);

        // Act
        $youtubeUploaderService->upload($this->pathFile, $this->title, $this->description, 'tag 1, tag 2, tag 3');
    }

    /**
     * Vérifie qu'aucun tag n'ai été envoyé si la chaîne de caractère est vide
     */
    public function testUpload_TagCase_EmptyArray() {
        // Arrange
        $videoUploaderMock = \Mockery::mock(YoutubeUploader::class);

        // Assert
        $videoUploaderMock->shouldReceive('upload')->once()
            ->with($this->pathFile, $this->title, $this->description, [ ], "");

        $youtubeUploaderService = new YoutubeUploaderService($videoUploaderMock);

        // Act
        $youtubeUploaderService->upload($this->pathFile, $this->title, $this->description);
    }

    /**
     * Vérifie qu'une illustratration est bien envoyé si elle est précisée dans les paramètres
     */
    public function testUpload_SendThumbnailCase_NominalCase() {
        // Arrange
        $videoUploaderMock = \Mockery::mock(YoutubeUploader::class);

        // Assert
        $videoUploaderMock->shouldReceive('upload')->once()
            ->with($this->pathFile, $this->title, $this->description, [ ], $this->thumbnail);

        $youtubeUploaderService = new YoutubeUploaderService($videoUploaderMock);

        // Act
        $youtubeUploaderService->upload($this->pathFile, $this->title, $this->description, "", $this->thumbnail);
    }

    /**
     * Vérifie que l'illustration ne fait pas plus de 2MB
     */
    public function testUpload_MaxFileExceededCase_ExpectException() {
        // Arrange
        $videoUploaderMock = \Mockery::mock(YoutubeUploader::class);

        // Assert
        $this->expectException(MaxFileExceededException::class);

        $youtubeUploaderService = new YoutubeUploaderService($videoUploaderMock);

        // Act
        $youtubeUploaderService->upload($this->pathFile, $this->title, $this->description, "tag 1, tag 2, tag 3", "app/uploads/max-file-exceeded.jpg");
    }

    /**
     * Verifie que l'id de la vidéo uploader est bien retourné et associé au contenu
     */
    public function testUpload_VideoIdCase_VideoIdSaved() {
        // Arrange
        $videoUploaderMock = \Mockery::mock(YoutubeUploader::class);

        $videoUploaderMock->shouldReceive('upload')->once()->andReturn('VIDEO_ID');

        $youtubeUploaderService = new YoutubeUploaderService($videoUploaderMock);

        // Act
        $videoId = $youtubeUploaderService->upload($this->pathFile, $this->title, $this->description, "tag 1, tag 2, tag 3");

        // Assert
        $this->assertEquals($videoId, "VIDEO_ID");
    }

    /**
     * Vérifie que le post existe bien, sinon retourne une erreur
     */
    public function testMoveVideoToPublicDirectory_Case_ExpectException() {
        // Arrange
        $file = UploadedFile::fake()->create('video.mp4', 1024);

        $videoUploaderMock = \Mockery::mock(YoutubeUploader::class);

        $youtubeUploaderService = new YoutubeUploaderService($videoUploaderMock);

        // Assert
        $this->expectException(ContentNotFoundException::class);

        // Act
        $youtubeUploaderService->moveVideoToPublicDirectory($file->hashName(), 12345);
    }

    /**
     * Vérifie que le vidéo a bien été déplacée dans le dossier public
     */
    public function testMoveVideoToPublicDirectory_CheckVideoFileCase_Success() {
        // Arrange
        $content = Content::find(1);

        $file = UploadedFile::fake()->create('video.mp4', 1024);
        copy(storage_path('app/uploads/intro.mp4'), storage_path('app/uploads/' . $file->hashName()));

        $videoUploaderMock = \Mockery::mock(YoutubeUploader::class);

        $youtubeUploaderService = new YoutubeUploaderService($videoUploaderMock);

        // Act
        $youtubeUploaderService->moveVideoToPublicDirectory($file->hashName(), $content['id']);

        // Assert
        $this->assertFileExists(storage_path('app/public/tutorial-video-' . $content['id'] . '-' . $content[Content::$VIDEO_ID] . '.mp4'));

        unlink(storage_path('app/public/tutorial-video-' . $content['id'] . '-' . $content[Content::$VIDEO_ID] . '.mp4'));
    }
}
