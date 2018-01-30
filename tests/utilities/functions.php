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
    $notMe = \App\User::orderBy('id', 'desc')->where('id', '!=', $me)->first();
    if (! empty($notMe)) return $notMe;

    $newUser = create('App\User', [], 2);
    return $newUser[0];
}

function getImage() {
    $from = '/home/sumi/桌面/785894d3jw1emyqyegyqij20by0bymy1.jpg';
    $to = 'storage/avatars/example.jpg';
    if (! file_exists($to)) copy($from, $to);
    return $to;
}

function dp($x) {
    (new \Illuminate\Support\Debug\Dumper)->dump($x);
}