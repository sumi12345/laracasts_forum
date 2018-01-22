<?php

namespace App;

use App\Scopes\RepliesCountScope;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

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

    public function getRepliesCountAttribute()
    {
        return $this->replies()->count();
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

    //----behavior----

    public function addReply($reply)
    {
        return $this->replies()->create($reply);
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
