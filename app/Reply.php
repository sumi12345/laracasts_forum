<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Reply extends Model
{
    protected $guarded = [];

    // relationship
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // relationship
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    // 当前登录用户给这条回复点赞
    public function favorite()
    {
        $this->favorites()->create([
            'user_id' => auth()->id(),
            'favorited_type' => static::class
        ]);
    }

    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }
}
