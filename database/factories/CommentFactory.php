<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'user_id' => User::all()->random()->id,
        'content' => $faker->paragraph,
        'parent' => Comment::all()->first() ? Comment::all()->random()->id : 0
    ];
});
