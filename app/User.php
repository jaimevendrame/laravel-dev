<?php

namespace lotecweb;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'idusu', 'role','username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getIdusu()
    {
        return $this->idusu;
    }

    public $rulesEdit = [
        'email' => 'email',
    ];

//    public function getPasswordAttribute($password){
//        return bcrypt($password);
//    }

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = strtolower($value);
    }
}
