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

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $this->favorites()->create([
            'user_id' => auth()->id(),
        ]);
    }

    public function unfavorite()
    {
        $this->favorites()->delete([
            'user_id' => auth()->id(),
        ]);
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