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
    $record = $class::orderBy('id', 'desc')->where('name', '!=', 'JohnDoe')->first();
    return $record ?: create($class);
}

function notMe()
{
    $users = \App\User::orderBy('id', 'desc')->where('name', '!=', 'JohnDoe')->take(2)->get();
    if (isset($users[1]) && (auth()->guest() || auth()->id() != $users[1]->id)) return $users[1];
    if (isset($users[0]) && auth()->check() && auth()->id() != $users[0]->id) return $users[0];

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