<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends Model implements Authenticatable
{
    use AuthenticableTrait;

    public static $FIRSTNAME = 'firstname';

    public static $LASTNAME = 'lastname';

    public static $EMAIL = 'email';

    public static $PASSWORD = 'password';

    public static $ROLE = 'role';

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['firstname', 'lastname', 'email', 'password', 'role'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];

    public function contents() {
        return $this->hasMany(Content::class);
    }
}
