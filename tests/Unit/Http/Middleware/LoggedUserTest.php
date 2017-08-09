<?php

namespace Tests\Unit\tests\Unit\Http\Middleware;

use App\Http\Middleware\LoggedUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class LoggedUserTest extends TestCase
{
    public function testHandleRedirectToAdmin()
    {
        // Arrange
        $userMocked = \Mockery::mock(User::class);

        $userMocked->shouldReceive('getAttribute')->once()->andReturn('admin');

        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn($userMocked);

        $requestMocked = \Mockery::mock(Request::class);

        $loggedUser = new LoggedUser();

        // Act
        $result = $loggedUser->handle($requestMocked, function() { });

        // Assert
        $this->assertContains('admin', $result->getTargetUrl());
    }

    public function testHandleRedirectToIndex()
    {
        // Arrange
        $userMocked = \Mockery::mock(User::class);

        $userMocked->shouldReceive('getAttribute')->once()->andReturn('customer');

        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn($userMocked);

        $requestMocked = \Mockery::mock(Request::class);

        $loggedUser = new LoggedUser();

        // Act
        $result = $loggedUser->handle($requestMocked, function() { });

        // Assert
        $this->assertContains('/', $result->getTargetUrl());
    }

    public function testHandleGoToAuthentication()
    {
        // Arrange
        Auth::shouldReceive('check')->once()->andReturn(false);

        $requestMocked = \Mockery::mock(Request::class);

        $loggedUser = new LoggedUser();

        // Act
        $result = $loggedUser->handle($requestMocked, function() {
            return true;
        });

        // Assert
        $this->assertTrue($result);
    }
}
