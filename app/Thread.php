<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use App\Scopes\RepliesCountScope;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected static function boot() {
        parent::boot();

        // 因为 5.1 版中没有 withCount 方法, repliescountscope 没有实现
        //static::addGlobalScope(new RepliesCountScope);

        static::deleting(function ($thread) {
            $thread->replies->each(function($reply) { $reply->delete(); });
        });

        static::created(function ($thread) {
            // 被 trait RecordsActivity 的 bootRecordsActivity 方法替代
            // $thread->recordActivity('created');
        });
    }

    public function path()
    {
        return '/threads/'.$this->channel->slug.'/'.$this->id;
    }

    //----relations----

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function activities()
    {
        return $this->hasMany('App\Activity');
    }

    public function subscriptions()
    {
        return $this->hasMany('App\ThreadSubscription');
    }

    //----attributes----

    public function getRepliesCountAttribute()
    {
        return $this->replies()->count();
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    //----behavior----

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        // 通知所有订阅这个帖子的用户
        // notify 方法由 User 使用的 Notifiable trait 提供
        // ThreadWasUpdated 是一个 Notification
        foreach ($this->subscriptions as $subscription) {
            $subscription->user->notify(new ThreadWasUpdated($this, $reply));
        }

        return $reply;
    }

    public function subscribe($userId = null)
    {
        return $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }


    //----scope----

    /**
     * Query Scope
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Filters\ThreadFilter $filter
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $filter)
    {
        return $filter->apply($query);
    }

}
