<?php

namespace App\Services;

use App\Exceptions\InvalidSubscriptionException;
use App\Helpers\GenerateStripeToken;
use Stripe\Stripe;

class PaymentService
{
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

        $user = $this->userService->registerUser($firstname, $lastname, $email, $password);

        $subscription = $user->newSubscription('monthly', 'RT0001')->create($token);

        if(empty($subscription)) {
            throw new InvalidSubscriptionException('Invalid subscription');
        }

        return true;
    }
}