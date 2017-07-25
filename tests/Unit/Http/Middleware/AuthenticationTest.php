<?php

namespace Tests\Unit\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function testHandleRedirectToAuthentication()
    {
        // Arrange
        Auth::shouldReceive('check')->once()->andReturn(false);
        $requestMock = \Mockery::mock(Request::class);

        $requestMock->shouldReceive('path')->once()->andReturn('/app/admin');

        $authentication = new \App\Http\Middleware\Authentication();

        // Act
        $result = $authentication->handle($requestMock, function($request) { });

        // Assert
        $this->assertEquals('http://localhost/authentication', $result->getTargetUrl());
    }

    public function testHandleRedirectToAppAdmin()
    {
        // Arrange
        Auth::shouldReceive('check')->twice()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn(new User([
            'role' => 'admin'
        ]));

        $requestMock = \Mockery::mock(Request::class);

        $requestMock->shouldReceive('path')->once()->andReturn('/authentication');

        $authentication = new \App\Http\Middleware\Authentication();

        // Act
        $result = $authentication->handle($requestMock, function($request) { });

        // Assert
        $this->assertEquals('http://localhost/app/admin', $result->getTargetUrl());
    }

    public function testHandleRedirectToApp()
    {
        // Arrange
        Auth::shouldReceive('check')->twice()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn(new User([
            'role' => 'customer'
        ]));

        $requestMock = \Mockery::mock(Request::class);

        $requestMock->shouldReceive('path')->once()->andReturn('/authentication');

        $authentication = new \App\Http\Middleware\Authentication();

        // Act
        $result = $authentication->handle($requestMock, function($request) { });

        // Assert
        $this->assertEquals('http://localhost/app', $result->getTargetUrl());
    }

    public function testHandleNotRedirectFromApp()
    {
        // Arrange
        Auth::shouldReceive('check')->twice()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn(new User([
            'role' => 'customer'
        ]));

        $requestMock = \Mockery::mock(Request::class);

        $requestMock->shouldReceive('path')->twice()->andReturn('/app');

        $authentication = new \App\Http\Middleware\Authentication();

        // Act
        $authentication->handle($requestMock, function() {
            // Assert
            $this->assertEquals(true, true);
        });
    }

    public function testHandleNotRedirectFromAppContents()
    {
        // Arrange
        Auth::shouldReceive('check')->twice()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn(new User([
            'role' => 'customer'
        ]));

        $requestMock = \Mockery::mock(Request::class);

        $requestMock->shouldReceive('path')->twice()->andReturn('/app/contents');

        $authentication = new \App\Http\Middleware\Authentication();

        // Act
        $authentication->handle($requestMock, function() {
            // Assert
            $this->assertEquals(true, true);
        });
    }
}
