<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ScopeInterface;

class RepliesCountScope implements ScopeInterface {

    public function apply(Builder $builder, Model $model)
    {
        $builder->with('replies');
    }

    public function remove(Builder $builder, Model $model)
    {
        parent::remove();
    }

}