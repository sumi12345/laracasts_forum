<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\User;

class ThreadFilter
{
    private $builder;

    private $filters = ['by'];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 添加 query builder 的条件
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->filters as $filter) {
            $this->builder = $this->$filter();
        }

        return $this->builder;
    }

    /**
     * 按用户名筛选帖子
     * @return \Illuminate\Database\Eloquent\Builder $builder
     */
    private function by()
    {
        $username = $this->request->by;
        if (! $username ) return $this->builder;

        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    /**
     * 按人气排序
     */
    private function popular()
    {
        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }
}