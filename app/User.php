<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'avatar_path'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'email', 'avatar_path'];

    protected $appends = ['avatar'];

    protected $casts = [
        'confirmed' => 'boolean',
    ];

    //----attributes----

    public function getAvatarAttribute()
    {
        return asset('avatars/'. ($this->avatar_path ?: 'default.jpg'));
    }

    //----relationships----

    public function lastReply()
    {
        return $this->hasOne('App\Reply')->orderBy('id', 'desc');
    }

    public function threads()
    {
        return $this->hasMany('App\Thread');
    }

    public function activities()
    {
        return $this->hasMany('App\Activity');
    }
    
    //----behaviors----

    public function read($thread)
    {
        $key = 'users.'.auth()->id().'.visits.'.$thread->id;
        \Cache::forever($key, \Carbon\Carbon::now());
    }

    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;

        $this->save();
    }
}
