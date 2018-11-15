<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

/**
 * @property User $user
 */
class Ad extends Model
{

    public static function boot()
    {
        parent::boot();

        /**
         * After ad has already persisted to the database.
         */
        static::saved(function ($item) {
            $item->pushToSocial();
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'price'
    ];

    protected $hidden = [
        'user_id'
    ];

    protected $with = [
        'user'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser($query, $id) {
        return $query->where('user_id', $id);
    }

    /**
     * Once the database record has been saved, it will push to social media. We could use a queued job for this so it
     * does not affect the saving process.
     */
    public function pushToSocial()
    {}
}
