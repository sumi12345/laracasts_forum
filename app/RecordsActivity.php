<?php

namespace App;


trait RecordsActivity
{
    // 需要记录的事件
    protected static $recordEvents = ['created'];

    // laravel trait boot 详细用法参见以下链接
    // http://www.archybold.com/blog/post/booting-eloquent-model-traits
    protected static function bootRecordsActivity()
    {
        // 未登录时不记录
        // if (auth()->guest()) return;

        static::created(function($model) {
            $model->recordActivity('created');
        });

        /* foreach (static::$recordEvents as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }*/

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }
    
    /**
     * @param string $event
     */
    protected function recordActivity($event)
    {
        $this->activity()->create([
            'type' => $event . '_' . strtolower((new \ReflectionClass($this))->getShortName()),
            'user_id' => $this->user_id,
            // 'subject_id' => $this->id,
            // 'subject_type' => get_class($this)
        ]);
    }

    public function activity()
    {
        // 自动填充 subject_id 和 subject_type
        return $this->morphMany('App\Activity', 'subject');
    }
}