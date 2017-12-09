<?php

namespace App;

use App\Scopes\RepliesCountScope;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $guarded = [];

    protected static function boot() {
        parent::boot();

        // 因为 5.1 版中没有 withCount 方法, repliescountscope 没有实现
        //static::addGlobalScope(new RepliesCountScope);
    }

    public function path()
    {
        return '/threads/'.$this->channel->slug.'/'.$this->id;
    }

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

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

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
