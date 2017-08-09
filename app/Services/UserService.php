<?php
namespace App\Services;


use App\Exceptions\BadCredentialsException;
use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\PasswordRequiredException;
use App\Exceptions\UnexpectedException;
use App\Exceptions\UserNotFoundException;
use App\Jobs\UnSubscribePlan;
use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Hash;

class UserService
{
    use DispatchesJobs;

    /** @var User */
    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function registerUser(string $firstname, string $lastname, string $email, string $password): User {
        $this->isEmailExists(0, $email, 1);

        return $this->user->create([
            User::$FIRSTNAME => $firstname,
            User::$LASTNAME => $lastname,
            User::$EMAIL => $email,
            User::$PASSWORD => bcrypt($password),
            User::$ROLE => User::CUSTOMER
        ]);
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

        $this->isEmailExists($id, $email, 2);

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

    public function delete(int $id): bool {
        $user = $this->user->where('id', $id)->first();

        if(empty($user)) {
            return false;
        }

        $this->cancelSubscription($user, true);

        return true;
    }

    public function cancelSubscription(User $user, bool $delete = false) {
        $this->dispatch(new UnSubscribePlan($user->id, $delete));
    }

    private function isEmailExists(int $id, string $email, int $acceptance = 1) {
        $n = $this->user->where(User::$EMAIL, $email)->where('id', '!=', $id)->count();

        if($n >= $acceptance) {
            throw new EmailAlreadyExistsException('Email already exists.');
        }
    }
}