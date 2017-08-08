<?php

namespace Tests\Unit;

use App\Http\Middleware\ProtectedContent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProtectedContentTest extends TestCase
{
    public function testHandleWithLogoutUser()
    {
        // Arrange
//        $userMocked = \Mockery::mock(User::class);
//
//        $userMocked->shouldReceive('getAttribute')->once()->with('role')->andReturn('customer');

        Auth::shouldReceive('check')->once()->andReturn(false);
//        Auth::shouldReceive('user')->once()->andReturn($userMocked);
        Auth::shouldReceive('logout')->once()->andReturn(true);

        $requestMock = \Mockery::mock(Request::class);

        $protectedContent = new ProtectedContent();

        // Act
        $result = $protectedContent->handle($requestMock, function($request) { });

        // Assert
        $this->assertContains('authentication', $result->getTargetUrl());
    }

    public function testHandleWithLoggedUserAndCustomerRole()
    {
        // Arrange
        $userMocked = \Mockery::mock(User::class);

        $userMocked->shouldReceive('getAttribute')->once()->with('role')->andReturn('customer');

        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn($userMocked);
        Auth::shouldReceive('logout')->once()->andReturn(true);

        $requestMock = \Mockery::mock(Request::class);

        $protectedContent = new ProtectedContent();

        // Act
        $result = $protectedContent->handle($requestMock, function($request) { });

        // Assert
        $this->assertContains('authentication', $result->getTargetUrl());
    }

    public function testHandleWithSuccess()
    {
        // Arrange
        $userMocked = \Mockery::mock(User::class);

        $userMocked->shouldReceive('getAttribute')->once()->andReturn('admin');

        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn($userMocked);

        $requestMock = \Mockery::mock(Request::class);

        $protectedContent = new ProtectedContent();

        // Act
        $result = $protectedContent->handle($requestMock, function($request) {
            return true;
        });

        // Assert
        $this->assertTrue($result);
    }
}
