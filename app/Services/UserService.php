<?php
namespace App\Services;


use App\Exceptions\BadCredentialsException;
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
}