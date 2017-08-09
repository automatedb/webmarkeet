<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProtectedDownloadTest extends TestCase
{
    public function testHandleRedirectToAuthenticationForCustomer1()
    {
        // Arrange
        Auth::shouldReceive('check')->once()->andReturn(false);
        Auth::shouldReceive('logout')->once()->andReturn(true);
        $requestMock = \Mockery::mock(Request::class);

        $protectedDownload = new \App\Http\Middleware\ProtectedDownload();

        // Act
        $result = $protectedDownload->handle($requestMock, function($request) { });

        // Assert
        $this->assertContains('authentication', $result->getTargetUrl());
    }

    public function testHandleRedirectToAuthenticationForCustomer2()
    {
        // Arrange
        $userMock = \Mockery::mock(User::class);

        $userMock->shouldReceive('subscribed')->once()->andReturn(false);
        $userMock->shouldReceive('getAttribute')->once()->with('role')->andReturn('customer');

        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('logout')->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn($userMock);
        $requestMock = \Mockery::mock(Request::class);

        $requestMock->shouldReceive('session')->once()->andReturn($requestMock);
        $requestMock->shouldReceive('flash')->once()->andReturn(null);

        $protectedDownload = new \App\Http\Middleware\ProtectedDownload();

        // Act
        $result = $protectedDownload->handle($requestMock, function($request) { });

        // Assert
        $this->assertContains('authentication', $result->getTargetUrl());
    }

    public function testHandleWithSuccess()
    {
        // Arrange
        $userMock = \Mockery::mock(User::class);

        $userMock->shouldReceive('subscribed')->once()->andReturn(true);

        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn($userMock);
        $requestMock = \Mockery::mock(Request::class);

        $protectedDownload = new \App\Http\Middleware\ProtectedDownload();

        // Act
        $result = $protectedDownload->handle($requestMock, function($request) {
            return true;
        });

        // Assert
        $this->assertTrue($result);
    }

    public function testHandleWithSuccessWithAdmin()
    {
        // Arrange
        $userMock = \Mockery::mock(User::class);

        $userMock->shouldReceive('getAttribute')->once()->with('role')->andReturn('admin');
        $userMock->shouldReceive('subscribed')->once()->andReturn(false);

        Auth::shouldReceive('check')->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn($userMock);
        $requestMock = \Mockery::mock(Request::class);

        $protectedDownload = new \App\Http\Middleware\ProtectedDownload();

        // Act
        $result = $protectedDownload->handle($requestMock, function($request) {
            return true;
        });

        // Assert
        $this->assertTrue($result);
    }
}
