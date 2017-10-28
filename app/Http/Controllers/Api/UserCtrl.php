<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\RequireFieldsException;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

/**
 * @Controller(prefix="/api/v1")
 */
class UserCtrl extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Show authentication blog
     * @Post("/facebook-registration    ")
     */
    public function facebookRegistration(Request $request) {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'facebook_id' => 'required'
        ];

        $values = $request->all();

        $validator = Validator::make($values, $rules);

        if($validator->fails()) {
            return response()->json(array(
                'message' => 'fields_required'
            ));
        }

        try {
            $this->userService->registerUser(
                $values[User::$FIRSTNAME],
                $values[User::$LASTNAME],
                $values[User::$EMAIL],
                str_random(8),
                $values[User::$FACEBOOK_ID]
            );
        } catch (\Exception $e) {
            return response()->json(array(
                'message' => 'failure_registration'
            ));
        }

        return response()->json([
            'message' => 'success_registration'
        ]);
    }
}
