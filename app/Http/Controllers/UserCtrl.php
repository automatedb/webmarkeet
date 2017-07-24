<?php

namespace App\Http\Controllers;

use App\Exceptions\BadCredentialsException;
use App\Exceptions\RequireFieldsException;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCtrl extends Controller
{
    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Show authentication blog
     * @Middleware("web")
     * @Get("/authentication", as="login")
     */
    public function authentication(Request $request) {
        return response()->view('User/authentication', [
            'alert' => $request->session()->get('alert')
        ]);
    }

    /**
     * Show authentication blog
     * @Middleware("web")
     * @Post("/post-authentication")
     */
    public function postAuthentication(Request $request) {
        if (empty($request->email) || empty($request->password)) {
            throw new RequireFieldsException('Tous les champs sont requis');
        }

        try {
            $user = $this->userService->authentication($request->email, $request->password);
        } catch (BadCredentialsException $e) {
            $request->session()->flash('alert', [
                'message' => 'Vos identifiants sont invalides.',
                'type' => 'warning'
            ]);

            return redirect()->action('UserCtrl@authentication');
        }

        Auth::login($user, true);

        return redirect()->action('AdminCtrl@index');
    }
}
