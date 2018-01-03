<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 17/12/24
 * Time: 10:59
 */

namespace App;


trait Favoritable
{
    protected static function bootFavoritable() {
        static::deleting(function ($model) {
            $model->favorites->each(function($favorite) {
                $favorite->delete();
            });
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $this->favorites()->create([
            'user_id' => auth()->id(),
            'favorited_type' => get_class($this)
        ]);
    }

    public function unfavorite()
    {
        $this->favorites()->where(['user_id' => auth()->id()])->get()->each(function($favorite) {
            $favorite->delete();
        });
    }

    public function isFavorited()
    {
        return $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}