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

    /**
     * @Post("upload/youtube")
     */
    public function index(Request $request) {
        $rules = [
            'id' => 'required',
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
        $values['description'] = !empty($values['description']) ? $values['description'] : "";

        if(!$request->hasFile('video')) {
            return response()->json([
                'message' => 'unexpected_error'
            ]);
        }

        $request->file('video')->store(config('content.uploadDirectory'));

        $thumbnailName = "";

        if($request->hasFile('thumbnail')) {
            $request->file('thumbnail')->store(config('content.uploadDirectory'));
            $thumbnailName = $request->file('thumbnail')->hashName();
        }

        $this->dispatch(new YoutubeUpload(
            $values['id'],
            $request->file('video')->hashName(),
            $values['title'],
            $values['description'],
            $values['tags'],
            $thumbnailName));

        return response()->json([
            'message' => 'success'
        ]);
    }
}
