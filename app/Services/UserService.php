<?php
namespace App\Services;


use App\Exceptions\BadCredentialsException;
use App\Exceptions\PasswordRequiredException;
use App\Exceptions\UnexpectedException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function authentication(string $email, string $password): User {
        $user = $this->user->where('email', $email)->first();

        if(empty($user)) {
            throw new UserNotFoundException('User not found');
        }

        if(!Hash::check($password, $user->password)) {
            throw new BadCredentialsException('Bad credentials');
        }

        return $user;
    }

    public function update(int $id, string $firstname, string $lastname, string $email, string $oldpassword = null, string $newpassword = null): User {
        if(!empty($newpassword) && empty($oldpassword) || empty($newpassword) && !empty($oldpassword)) {
            throw new PasswordRequiredException('Old password and new password are required');
        }

        /** @var User */
        $user = $this->user->where('id', $id)->first();

        if(empty($user)) {
            throw new UserNotFoundException('User not found');
        }

        $user[User::$FIRSTNAME] = $firstname;
        $user[User::$LASTNAME] = $lastname;
        $user[User::$EMAIL] = $email;

        if(!empty($oldpassword) && !Hash::check($oldpassword, $user->password)) {
            throw new BadCredentialsException('Bad password');
        } else if(!empty($newpassword)) {
            $user[User::$PASSWORD] = bcrypt($newpassword);
        }

        if(!$user->save()) {
            throw new UnexpectedException('Unexpected error');
        }

        return $user;
    }
}