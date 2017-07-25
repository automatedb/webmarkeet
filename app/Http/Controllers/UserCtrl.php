<?php

namespace App\Http\Controllers;

use App\Exceptions\BadCredentialsException;
use App\Exceptions\RequireFieldsException;
use App\Exceptions\UserNotFoundException;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserCtrl
 * @Middleware("web")
 */
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
    public function authentication(Request $request) {
        return response()->view('User/authentication', [
            'alert' => $request->session()->get('alert')
        ]);
    }

    /**
     * Show authentication blog
     * @Post("/post-authentication")
     */
    public function postAuthentication(Request $request) {
        $redirect = 'UserCtrl@authentication';

        if (empty($request->email) || empty($request->password)) {
            throw new RequireFieldsException('Tous les champs sont requis');
        }

        try {
            $user = $this->userService->authentication($request->email, $request->password);

            Auth::login($user);

            $redirect = 'AdminCtrl@index';
        } catch (BadCredentialsException | UserNotFoundException $e) {
            $request->session()->flash('alert', [
                'message' => 'Vos identifiants sont invalides.',
                'type' => 'warning'
            ]);
        }

        return redirect()->action($redirect);
    }
}
