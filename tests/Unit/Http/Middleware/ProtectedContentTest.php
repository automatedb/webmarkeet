<?php

namespace Tests\Unit;

use App\Http\Middleware\ProtectedContent;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProtectedContentTest extends TestCase
{
    public function testHandleWithLogoutUser()
    {
        // Arrange
        Auth::shouldReceive('check')->once()->andReturn(false);
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
        Auth::shouldReceive('check')->once()->andReturn(true);

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
