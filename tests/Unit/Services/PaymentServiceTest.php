<?php

namespace Tests\Unit\Services;

use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\InvalidCardException;
use App\Exceptions\InvalidSubscriptionException;
use App\Helpers\GenerateStripeToken;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\UserService;
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

        $userMock = \Mockery::mock(User::class);

        $mockGenerateStripeToken = \Mockery::mock(GenerateStripeToken::class);
        $mockGenerateStripeToken->shouldReceive('getToken')->once()->andThrow(InvalidCardException::class);

        $mock->shouldReceive('registerUser')->once()->andReturn($userMock);

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

        $mock->shouldReceive('registerUser')->once()->andReturn($userMock);

        $paymentService = new PaymentService($mock, $mockGenerateStripeToken);

        // Assert
        $this->expectException(InvalidSubscriptionException::class);

        // Act
        $paymentService->payment('firstname', 'lastname', 'john.doe@domain.tld', 'password', 'number-card', 'exp-month', 'exp-year', 'cvc');
    }

    public function testPaymentWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(UserService::class);

        $userMock = \Mockery::mock(User::class);

        $userMock->shouldReceive('newSubscription')->once()->andReturn($userMock);
        $userMock->shouldReceive('create')->once()->andReturn(new Subscription());

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
