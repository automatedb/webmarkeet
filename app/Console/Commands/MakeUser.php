<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use League\Flysystem\Exception;

class MakeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user {--firstname=} {--lastname=} {--email=} {--password=} {--role=customer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Permet la création d'un utilisateur" ;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $firstname = $this->option('firstname');
        $lastname = $this->option('lastname');
        $email = $this->option('email');
        $password = $this->option('password');
        $role = $this->option('role');

        $user = new User();

        $user[User::$FIRSTNAME] = $firstname;
        $user[User::$LASTNAME] = $lastname;
        $user[User::$EMAIL] = $email;
        $user[User::$PASSWORD] = bcrypt($password);
        $user[User::$ROLE] = $role;

        if(!$user->save()) {
            throw new Exception("User couldn't saved.");
        }

        var_dump("New user saved.");
    }
}
