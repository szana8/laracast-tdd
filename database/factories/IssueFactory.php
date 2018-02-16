<?php

use Faker\Generator as Faker;

$factory->define(\App\Issue::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'category_id' => function () {
            return factory('App\Category')->create()->id;
        },
        'summary' => $faker->sentence,
        'description' => $faker->paragraph
    ];
});


$factory->define(\App\Category::class, function (Faker $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => $name,
        'description' => $faker->paragraph
    ];
});

//$factory->define(\App\Subtask::class, function (Faker $faker) {
//    return [
//        'user_id' => function () {
//            return factory('App\User')->create()->id;
//        },
//        'issue_id' => function () {
//            return factory('App\Issue')->create()->id;
//        },
//        'body' => $faker->paragraph
//    ];
//});


$factory->define(\App\Reply::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'issue_id' => function () {
            return factory('App\Issue')->create()->id;
        },
        'body' => $faker->paragraph
    ];
});

