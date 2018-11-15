<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    public static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->hashPassword();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'auth_token', 'created_at', 'updated_at', 'id'
    ];

    public function hashPassword()
    {
        $this->attributes['auth_token'] = sha1($this->auth_token);
    }

    public static function checkCredentials($challenge)
    {
        return static::where('auth_token', sha1($challenge))->first();
    }
}
