<?php

namespace Tests\Unit\Services;

use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\InvalidCardException;
use App\Exceptions\InvalidSubscriptionException;
use App\Helpers\GenerateStripeToken;
use App\Mail\SubscriptionConfirmed;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\UserService;
use Illuminate\Mail\PendingMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Laravel\Cashier\Subscription;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    public function testPaymentWithEmailAlreadyExistsException() {
        // Arrange
        $mock = \Mockery::mock(UserService::class);

        $mockGenerateStripeToken = \Mockery::mock(GenerateStripeToken::class);

        $mock->shouldReceive('registerUser')->once()->andThrow(EmailAlreadyExistsException::class);

        $paymentService = new PaymentService($mock, $mockGenerateStripeToken);

        // Assert
        $this->expectException(EmailAlreadyExistsException::class);

        // Act
        $paymentService->payment('firstname', 'lastname', 'john.doe@domain.tld', 'password', 'number-card', 'exp-month', 'exp-year', 'cvc');

    }

    public function testPaymentWithInvalidCardException() {
        // Arrange
        $mock = \Mockery::mock(UserService::class);

        $mockGenerateStripeToken = \Mockery::mock(GenerateStripeToken::class);
        $mockGenerateStripeToken->shouldReceive('getToken')->once()->andThrow(InvalidCardException::class);

        $paymentService = new PaymentService($mock, $mockGenerateStripeToken);

        // Assert
        $this->expectException(InvalidCardException::class);

        // Act
        $paymentService->payment('firstname', 'lastname', 'john.doe@domain.tld', 'password', 'number-card', 'exp-month', 'exp-year', 'cvc');
    }

    public function testPaymentWithInvalidPlan() {
        // Arrange
        $mock = \Mockery::mock(UserService::class);

        $mockGenerateStripeToken = \Mockery::mock(GenerateStripeToken::class);
        $mockGenerateStripeToken->shouldReceive('getToken')->once()->andReturn('token-string');

        $userMock = \Mockery::mock(User::class);

        $userMock->shouldReceive('newSubscription')->once()->andReturn($userMock);
        $userMock->shouldReceive('create')->once()->andReturn(null);
        $userMock->shouldReceive('forceDelete')->once()->andReturn(true);

        $mock->shouldReceive('registerUser')->once()->andReturn($userMock);

        $paymentService = new PaymentService($mock, $mockGenerateStripeToken);

        // Assert
        $this->expectException(InvalidSubscriptionException::class);

        // Act
        $paymentService->payment('firstname', 'lastname', 'john.doe@domain.tld', 'password', 'number-card', 'exp-month', 'exp-year', 'cvc');
    }

    public function testPaymentWithSuccess() {
        // Arrange
        $mockMail = \Mockery::mock(PendingMail::class);

        Mail::shouldReceive('to')->once()->andReturn($mockMail);

        $mock = \Mockery::mock(UserService::class);

        $userMock = \Mockery::mock(new User([
            User::$FIRSTNAME => 'firstname',
            User::$LASTNAME => 'lastname'
        ]));

        $userMock->shouldReceive('newSubscription')->once()->andReturn($userMock);
        $userMock->shouldReceive('create')->once()->andReturn(true);

        $mockGenerateStripeToken = \Mockery::mock(GenerateStripeToken::class);
        $mockGenerateStripeToken->shouldReceive('getToken')->once()->andReturn('token-string');

        $mock->shouldReceive('registerUser')->once()->andReturn($userMock);

        $paymentService = new PaymentService($mock, $mockGenerateStripeToken);

        // Act
        $result = $paymentService->payment('firstname', 'lastname', 'john.doe@domain.tld', 'password', 'number-card', 'exp-month', 'exp-year', 'cvc');

        // Assert
        $this->assertTrue($result);
    }
}
