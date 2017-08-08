<?php

namespace Tests\Unit\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function testHandleRedirectToAuthenticationFromAdmin1()
    {
        // Arrange
        Auth::shouldReceive('check')->once()->andReturn(false);
        Auth::shouldReceive('logout')->once()->andReturn(true);
        $requestMock = \Mockery::mock(Request::class);

        $authentication = new \App\Http\Middleware\Authentication();

        // Act
        $result = $authentication->handle($requestMock, function($request) { });

        // Assert
        $this->assertContains('authentication', $result->getTargetUrl());
    }

    public function testHandleRedirectToAuthenticationFromAdmin2()
    {
        // Arrange
        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('logout')->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn(new User([
            'role' => 'customer'
        ]));
        $requestMock = \Mockery::mock(Request::class);

        $authentication = new \App\Http\Middleware\Authentication();

        // Act
        $result = $authentication->handle($requestMock, function($request) { });

        // Assert
        $this->assertContains('authentication', $result->getTargetUrl());
    }

    public function testHandleWithSuccess()
    {
        // Arrange
        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn(new User([
            'role' => 'admin'
        ]));
        $requestMock = \Mockery::mock(Request::class);

        $authentication = new \App\Http\Middleware\Authentication();

        // Act
        $result = $authentication->handle($requestMock, function($request) {
            return true;
        });

        // Assert
        $this->assertTrue($result);
    }
}
