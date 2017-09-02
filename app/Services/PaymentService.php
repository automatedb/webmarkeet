<?php

namespace App\Services;

use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\InvalidCardException;
use App\Exceptions\InvalidSubscriptionException;
use App\Helpers\GenerateStripeToken;
use App\Mail\SubscriptionConfirmed;
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
        $token = $this->generateStripeToken->getToken($firstname, $lastname, $numberCard, $expMonth, $expYear, $cvc);

        try {
            $user = $this->userService->registerUser($firstname, $lastname, $email, $password);
        } catch (QueryException $e) {
            throw new EmailAlreadyExistsException('Email already exception');
        }

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

        Mail::to($email)->queue(new SubscriptionConfirmed());

        return true;
    }
}