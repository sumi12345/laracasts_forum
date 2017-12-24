<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Reply extends Model
{
    use Favoritable;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    // relationship
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // relationship

    // 当前登录用户给这条回复点赞

}
