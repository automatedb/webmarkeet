<?php

namespace App\Http\Controllers\Api;

use App\Jobs\YoutubeUpload;
use App\Services\YoutubeUploaderService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * @Controller(prefix="/api/v1")
 */
class UploadController extends Controller
{
    use DispatchesJobs;

    private $youtubeUploaderService;

    public function __construct(YoutubeUploaderService $youtubeUploaderService) {
        $this->youtubeUploaderService = $youtubeUploaderService;
    }

    /**
     * @Post("upload/youtube")
     */
    public function index(Request $request) {
        $rules = [
            'title' => 'required',
            'video' => 'required'
        ];

        $values = $request->all();

        $validation = Validator::make($values, $rules);

        if($validation->fails()) {
            return response()->json([
                'message' => 'unexpected_error'
            ]);
        }

        $values['tags'] = !empty($values['tags']) ? $values['tags'] : "";

        $request->file('video')->store(config('content.uploadDirectory'));
        $request->file('thumbnail')->store(config('content.uploadDirectory'));

        $this->dispatch(new YoutubeUpload(
            $request->file('video')->hashName(),
            $values['title'],
            $values['description'],
            $values['tags'],
            $request->file('thumbnail')->hashName()));
    }
}
