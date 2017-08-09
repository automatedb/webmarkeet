<?php

namespace App\Http\Controllers;

use App\Exceptions\BadCredentialsException;
use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\InvalidCardException;
use App\Exceptions\RequireFieldsException;
use App\Exceptions\UnexpectedException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserCtrl
 * @Middleware("web")
 */
class UserCtrl extends Controller
{
    private $userService;

    private $paymentService;

    public function __construct(UserService $userService, PaymentService $paymentService) {
        $this->userService = $userService;
        $this->paymentService = $paymentService;
    }

    /**
     * Show profil page
     * @Get("/profile")
     * @Middleware("customer")
     */
    public function profile(Request $request) {
        return response()->view('User.profile', [
            'alert' => $request->session()->get('alert'),
            'user' => Auth::user()
        ]);
    }

    /**
     * Show index customer page
     * @Post("/profile/update")
     * @Middleware("customer")
     */
    public function modify(Request $request) {
        $user = Auth::user();
        $keyOldPassword = 'oldpassword';

        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required'
        ];

        $values = $request->all();

        $validator = Validator::make($values, $rules);

        if($validator->fails()) {
            throw new RequireFieldsException("Tous les champs sont requis.");
        }

        $passwordRules = [
            $keyOldPassword => 'required',
            'password' => 'required',
            'confirm' => 'required|same:password'
        ];

        $validator = Validator::make($values, $passwordRules);

        $errors = $validator->errors()->toArray();

        $nErrors = count($errors);
        $changePasswordBlocked = false;
        $messagePassword = '';

        if($nErrors < 3) {
            // User has only entered a password and a confirm
            if(key_exists($keyOldPassword, $errors) && $nErrors === 1) {
                $changePasswordBlocked = true;
                $messagePassword = "Par contre, nous ne pouvons pas changer votre mot de passe. Pour cela vous devez saisir votre mot de passe courant.";
            // User has only entered an old password
            } else if (!key_exists($keyOldPassword, $errors) && $nErrors === 2) {
                $changePasswordBlocked = true;
                $messagePassword = "Par contre, vous avez saisi votre mot de passe courant, mais pas de nouveau mot de passe, nous n'avons donc pas changer votre mot de passe.";
                // User has only entered an old password with either a password or confirmation
            } else if(!key_exists($keyOldPassword, $errors) && $nErrors === 1) {
                $changePasswordBlocked = true;
                $messagePassword = "Par contre, le mot de passe saisi est différent de sa confirmation. Nous n'avons donc pas changer votre mot de passe.";
            }
        }

        try {
            if(!$changePasswordBlocked) {
                $this->userService->update($user->id, $values[User::$FIRSTNAME], $values[User::$LASTNAME], $values[User::$EMAIL], $values[$keyOldPassword], $values[User::$PASSWORD]);
            } else {
                $this->userService->update($user->id, $values[User::$FIRSTNAME], $values[User::$LASTNAME], $values[User::$EMAIL]);
            }

            $request->session()->flash('alert', [
                'message' => sprintf('Vos informations ont été modifiées. %s', $messagePassword),
                'type' => 'success'
            ]);
        } catch (UserNotFoundException | UnexpectedException $e) {
            throw new UnexpectedException("Erreur non défini");
        } catch (BadCredentialsException $e) {
            $request->session()->flash('alert', [
                'message' => 'Votre ancien mot de passe est invalide.',
                'type' => 'warning'
            ]);
        }

        return redirect()->back();
    }

    /**
     * Show authentication blog
     * @Get("/authentication")
     * @Middleware("logged")
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
        if (empty($request->email) || empty($request->password)) {
            throw new RequireFieldsException('Tous les champs sont requis');
        }

        try {
            $user = $this->userService->authentication($request->email, $request->password);

            Auth::login($user, true);

            $redirect = ($user->role === 'admin') ? 'AdminCtrl@index' : 'IndexCtrl@index';

            return redirect()->action($redirect);
        } catch (BadCredentialsException | UserNotFoundException $e) {
            $request->session()->flash('alert', [
                'message' => 'Vos identifiants sont invalides.',
                'type' => 'warning'
            ]);
        }

        return redirect()->back();
    }

    /**
     * @Get("/delete")
     * @Middleware("customer")
     */
    public function delete(Request $request) {
        $user = Auth::user();

        if(!$this->userService->delete($user->id)) {
            $request->session()->flash('alert', [
                'message' => "Il nous est impossible de supprimer votre compte. Notre équipe a été informé de ce problème. Nous vous prions de nous excuser et vous invitons à recommencer ultérieurement.",
                'type' => 'warning'
            ]);

            return redirect()->back();
        };

        Auth::logout();

        $request->session()->flash('alert', [
            'message' => "Votre compte est supprimé. Nous sommes désolés de vous voir partir. Merci de la confiance que vous nous avez accordé jusqu'à présent. Cordialement.",
            'type' => 'success'
        ]);

        return redirect()->action('UserCtrl@authentication');
    }

    /**
     * @Get("/logout")
     * @Middleware("customer")
     */
    public function logout() {
        Auth::logout();

        return redirect()->action('IndexCtrl@index');
    }

    /**
     * @Get("/payment")
     */
    public function payment(Request $request) {
        return response()->view('User.payment', [
            'alert' => $request->session()->get('alert')
        ]);
    }

    /**
     * @Post("/payment")
     */
    public function postPayment(Request $request) {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'password' => 'required',
            'confirm' => 'required',
            'card_number' => 'required',
            'exp_month' => 'required',
            'exp_year' => 'required',
            'cvc' => 'required',
        ];

        $values = $request->all();

        $validator = Validator::make($values, $rules);

        if($validator->fails()) {
            throw new RequireFieldsException("Tous les champs sont requis.");
        }

        $passwordRules = [
            'confirm' => 'same:password'
        ];

        $validator = Validator::make($values, $passwordRules);

        if($validator->fails()) {
            $request->session()->flash('alert', [
                'message' => 'Votre confirmation est différente de votre mot de passe.',
                'type' => 'warning'
            ]);

            return redirect()->back()->withInput();
        }

        try {
            $this->paymentService->payment(
                $values[User::$FIRSTNAME],
                $values[User::$LASTNAME],
                $values[User::$EMAIL],
                $values[User::$PASSWORD],
                $values['card_number'],
                $values['exp_month'],
                $values['exp_year'],
                $values['cvc']
            );
        } catch (InvalidCardException $e) {
            $request->session()->flash('alert', [
                'message' => 'Les informations de paiement ne sont pas valides.',
                'type' => 'warning'
            ]);

            return redirect()->back()->withInput();
        } catch (EmailAlreadyExistsException $e) {
            $request->session()->flash('alert', [
                'message' => 'Un compte est déjà existant avec cette adresse email.',
                'type' => 'warning'
            ]);

            return redirect()->back()->withInput();
        }

        return redirect()->action('UserCtrl@thanks');
    }

    /**
     * @Get("/payment/thanks")
     */
    public function thanks(Request $request) {
        return response()->view('User.payment-thanks', [
            'alert' => $request->session()->get('alert')
        ]);
    }

    /**
     * @Get("/payment/cancel")
     */
    public function unSubscribe(Request $request) {
        $this->userService->cancelSubscription(Auth::user());

        $request->session()->flash('alert', [
            'message' => 'Votre souscription a été annulé.',
            'type' => 'success'
        ]);

        return redirect()->back();
    }
}
