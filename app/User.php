<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App
 * @property string api_token
 */
class User extends Authenticatable
{
    use Notifiable;

    const TABLE_NAME = 'users';
    const FIELD_NAME = 'name';
    const FIELD_EMAIL = 'email';
    const FIELD_PASS = 'password';
    const FIELD_REMEMBER_TOKEN = 'remember_token';
    const FIELD_API_TOKEN = 'api_token';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::FIELD_NAME, self::FIELD_EMAIL, self::FIELD_PASS,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        self::FIELD_PASS, self::FIELD_REMEMBER_TOKEN
    ];

    /**
     * Generate an api token an set it to the current user
     * @return string The generated token
     */
    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();
        return $this->api_token;
    }
}
