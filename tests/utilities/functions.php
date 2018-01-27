<?php

function create($class, $attributes = [], $times = null)
{
    return factory($class, $times)->create($attributes);
}

function make($class, $attributes = [], $times = null)
{
    return factory($class, $times)->make($attributes);
}

function getLast($class)
{
    $record = $class::orderBy('id', 'desc')->first();
    return $record ?: create($class);
}

function notMe()
{
    $me = auth()->check() ? auth()->id() : getLast('App\User')->id;
    $notMe = \App\User::orderBy('id', 'desc')->where('id', '<', $me)->first();
    if (! empty($notMe)) return $notMe;

    $newUser = create('App\User', [], 2);
    return $newUser[0];
}