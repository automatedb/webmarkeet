<?php

namespace App\Services;

use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\InvalidCardException;
use App\Exceptions\InvalidSubscriptionException;
use App\Helpers\GenerateStripeToken;
use App\Mail\RenewalSubscriptionConfirmed;
use App\Mail\SubscriptionConfirmed;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Mail;
use Stripe\Error\Card;
use Stripe\Stripe;

class PaymentService
{
    use DispatchesJobs;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var GenerateStripeToken
     */
    private $generateStripeToken;

    public function __construct(UserService $userService, GenerateStripeToken $generateStripeToken)
    {
        Stripe::setApiKey(config('services.stripe.key'));

        $this->generateStripeToken = $generateStripeToken;
        $this->userService = $userService;
    }

    public function payment(string $firstname, string $lastname, string $email, string $password, string $numberCard, string $expMonth, string $expYear, string $cvc) {
        try {
            $user = $this->userService->registerUser($firstname, $lastname, $email, $password);
        } catch (QueryException $e) {
            throw new EmailAlreadyExistsException('Email already exception');
        }

        $this->subscription($user, $numberCard, $expMonth, $expYear, $cvc);

        Mail::to($email)->queue(new SubscriptionConfirmed());

        return true;
    }

    public function renewal(string $email, string $numberCard, string $expMonth, string $expYear, string $cvc) {
        $user = $this->userService->getUserByMail($email);

        $this->subscription($user, $numberCard, $expMonth, $expYear, $cvc);

        Mail::to($email)->queue(new RenewalSubscriptionConfirmed());
    }
    
    private function subscription(User $user, string $numberCard, string $expMonth, string $expYear, string $cvc) {
        $token = $this->generateStripeToken->getToken($user[User::$FIRSTNAME], $user[User::$LASTNAME], $numberCard, $expMonth, $expYear, $cvc);
        
        try {
            $subscription = $user->newSubscription('monthly', 'RT0001')->create($token);
        } catch (Card $e) {
            $user->forceDelete();
            throw new InvalidCardException('Invalid subscription');
        }

        if(empty($subscription)) {
            $user->forceDelete();
            throw new InvalidSubscriptionException('Invalid subscription');
        }
    }
}