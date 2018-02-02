<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($reply) {
            if ($reply->isBest) {
                $reply->thread->update(['best_reply_id' => null]);
            }
        });
    }

    //----relationships----

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    //----attributes----

    public function path()
    {
        return $this->thread->path().'#reply-'.$this->id;
    }

    public function wasJustPublished()
    {
        return $this->created_at > \Carbon\Carbon::now()->subMinute();
    }

    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    //----actions----

    public function markBestReply()
    {
        $this->thread->update(['best_reply_id' => $this->id]);
    }
}
