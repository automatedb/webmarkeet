<?php

namespace Tests\Feature\Services;

use App\Exceptions\BadCredentialsException;
use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\PasswordRequiredException;
use App\Exceptions\UnexpectedException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
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

    public function testUpdateWithNotFoundUser() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn(null);

        $userService = new UserService($mock);

        // Assert
        $this->expectException(UserNotFoundException::class);

        // Act
        $userService->update(1, 'jane', 'doe', 'jane.doe@domain.tld');
    }

    public function testUpdateWithOldPasswordRequired() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $userService = new UserService($mock);

        // Assert
        $this->expectException(PasswordRequiredException::class);

        // Act
        $userService->update(1, 'jane', 'doe', 'jane.doe@domain.tld', '', 'password');
    }

    public function testUpdateWithPasswordRequired() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $userService = new UserService($mock);

        // Assert
        $this->expectException(PasswordRequiredException::class);

        // Act
        $userService->update(1, 'jane', 'doe', 'jane.doe@domain.tld', 'password');
    }

    public function testUpdateWithEmailAlreadyExists() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $mock->shouldReceive('where')->times(3)->andReturn($mock);
        $mock->shouldReceive('count')->once()->andReturn(2);
        $mock->shouldReceive('first')->once()->andReturn(new User());

        $userService = new UserService($mock);

        // Assert
        $this->expectException(EmailAlreadyExistsException::class);

        // Act
        $userService->update(1, 'jane', 'doe', 'jane.doe@domain.tld');
    }

    public function testUpdatePasswordWithBadPassword() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $mock->shouldReceive('where')->times(3)->andReturn($mock);
        $mock->shouldReceive('count')->once()->andReturn(0);
        $mock->shouldReceive('first')->once()->andReturn(new User([
            'id' => 1,
            'firtname' => 'john',
            'lastname' => 'doe',
            'email' => 'john.doe@domain.tld',
            'password' => bcrypt('password')
        ]));

        $userService = new UserService($mock);

        // Assert
        $this->expectException(BadCredentialsException::class);

        // Act
        $userService->update(1, 'jane', 'doe', 'jane.doe@domain.tld', 'badpassword', 'newpassword');
    }

    public function testUpdatePasswordWithUnexpectedError() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $userMock = \Mockery::mock(new User([
            'id' => 1,
            'firstname' => 'john',
            'lastname' => 'doe',
            'email' => 'john.doe@domain.tld',
            'role' => 'customer',
            'password' => bcrypt('password')
        ]));

        $userMock->shouldReceive('save')->once()->andReturn(false);

        $mock->shouldReceive('where')->times(3)->andReturn($mock);
        $mock->shouldReceive('count')->once()->andReturn(0);
        $mock->shouldReceive('first')->once()->andReturn($userMock);

        $userService = new UserService($mock);

        // Assert
        $this->expectException(UnexpectedException::class);

        // Act
        $userService->update(1, 'jane', 'doe', 'jane.doe@domain.tld', 'password', 'newpassword');
    }

    public function testUpdatePasswordWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $userMock = \Mockery::mock(new User([
            'id' => 1,
            'firstname' => 'john',
            'lastname' => 'doe',
            'email' => 'john.doe@domain.tld',
            'role' => 'customer',
            'password' => bcrypt('password')
        ]));

        $userMock->shouldReceive('save')->once()->andReturn(true);

        $mock->shouldReceive('where')->times(3)->andReturn($mock);
        $mock->shouldReceive('count')->once()->andReturn(0);
        $mock->shouldReceive('first')->once()->andReturn($userMock);

        $userService = new UserService($mock);

        // Act
        $user = $userService->update(1, 'jane', 'doe', 'jane.doe@domain.tld', 'password', 'newpassword');

        // Assert
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    public function testUpdateWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $userMock = \Mockery::mock(new User([
            'id' => 1,
            'firstname' => 'jane',
            'lastname' => 'doe',
            'email' => 'john.doe@domain.tld',
            'role' => 'customer',
            'password' => bcrypt('password')
        ]));

        $userMock->shouldReceive('save')->once()->andReturn(true);

        $mock->shouldReceive('where')->times(3)->andReturn($mock);
        $mock->shouldReceive('count')->once()->andReturn(0);
        $mock->shouldReceive('first')->once()->andReturn($userMock);

        $userService = new UserService($mock);

        // Act
        $user = $userService->update(1, 'jane', 'doe', 'jane.doe@domain.tld');

        // Assert
        $this->assertEquals($user->firstname, 'jane');
        $this->assertEquals($user->role, 'customer');
    }

    public function testDeleteWithUserNotFound() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn(null);

        $userService = new UserService($mock);

        // Act
        $result = $userService->delete(1);

        // Assert
        $this->assertFalse($result);
    }

    public function testDeleteWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $userMock = \Mockery::mock(new User([]));

        $userMock->shouldReceive('delete')->andReturn(true);

        $mock->shouldReceive('where')->once()->andReturn($mock);
        $mock->shouldReceive('first')->once()->andReturn($userMock);

        $userService = new UserService($mock);

        // Act
        $result = $userService->delete(1);

        // Assert
        $this->assertTrue($result);
    }

    public function testRegisterUserWithEmailAlreadyExistsException() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $mock->shouldReceive('where')->twice()->andReturn($mock);
        $mock->shouldReceive('count')->once()->andReturn(1);

        $userService = new UserService($mock);

        // Assert
        $this->expectException(EmailAlreadyExistsException::class);

        // Act
        $userService->registerUser('firstname', 'lastname', 'john.doe@domain.tld', 'password');

    }

    public function testRegisterUserWithSuccess() {
        // Arrange
        $mock = \Mockery::mock(User::class);

        $mock->shouldReceive('where')->twice()->andReturn($mock);
        $mock->shouldReceive('count')->once()->andReturn(0);
        $mock->shouldReceive('create')->once()->andReturn(new User([
            User::$ROLE => User::CUSTOMER
        ]));

        $userService = new UserService($mock);

        // Act
        $user = $userService->registerUser('firstname', 'lastname', 'john.doe@domain.tld', 'password');


        // Assert
        $this->assertEquals($user->role, User::CUSTOMER);
    }
}
