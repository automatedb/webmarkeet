<?php

namespace App\Http\Controllers;

use App\Exceptions\RequireFieldsException;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserCtrl extends Controller
{
    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Show authentication blog
     * @Get("/authentication")
     */
    public function authentication() {
        return response()->view('User/authentication');
    }

    /**
     * Show authentication blog
     * @Post("/post-authentication")
     */
    public function postAuthentication(Request $request) {
        if (empty($request->email) || empty($request->password)) {
            throw new RequireFieldsException('Tous les champs sont requis');
        }

        $this->userService->authentication($request->email, $request->password);
    }
}
