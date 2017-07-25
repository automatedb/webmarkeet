<?php

namespace Tests\Feature\Services;

use App\Exceptions\BadCredentialsException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Services\UserService;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    public function testAuthenticationWithBadEmail() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn(null);

        $userService = new UserService($mock);

        // Assert
        $this->expectException(UserNotFoundException::class);

        // Act
        $userService->authentication('john.doe@domain.tld', 'password');
    }

    public function testAuthenticationWithBadPassword() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn((object) [
            'email' => 'john.doe@domain.tld',
            'password' => ''
        ]);

        $userService = new UserService($mock);

        // Assert
        $this->expectException(BadCredentialsException::class);

        // Act
        $userService->authentication('john.doe@domain.tld', 'password');
    }

    public function testAuthenticationWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn(new User([
            'email' => 'john.doe@domain.tld',
            'password' => bcrypt('password')
        ]));

        $userService = new UserService($mock);

        // Act
        $user = $userService->authentication('john.doe@domain.tld', 'password');

        // Assert
        $this->assertEquals($user->email, 'john.doe@domain.tld');
    }
}
