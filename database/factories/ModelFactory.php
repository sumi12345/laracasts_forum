<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $name = $faker->name;

    return [
        'name' => $name,
        'email' => $faker->email,
        'password' => bcrypt($name),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Thread::class, function (Faker\Generator $faker) {
    return [
        'user_id' => notMe()->id,
        'channel_id' => getLast('App\Channel')->id,
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
    ];
});

$factory->define(App\Reply::class, function (Faker\Generator $faker) {
    return [
        'user_id' => notMe()->id,
        'thread_id' => getLast('App\Thread')->id,
        'body' => $faker->paragraph,
    ];
});

$factory->define(App\Channel::class, function (Faker\Generator $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => $name
    ];
});

$factory->define(Illuminate\Notifications\DatabaseNotification::class, function (Faker\Generator $faker) {
    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type' => \App\Notifications\ThreadWasUpdated::class,
        'notifiable_id' => notMe()->id,
        'notifiable_type' => \App\User::class,
        'data' => ['message' => $faker->sentence]
    ];
});