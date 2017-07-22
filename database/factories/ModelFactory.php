<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker): array {
    static $password;

    return [
        \App\Models\User::$FIRSTNAME => $faker->firstName,
        \App\Models\User::$LASTNAME => $faker->lastName,
        \App\Models\User::$EMAIL => $faker->unique()->safeEmail,
        \App\Models\User::$PASSWORD => $password ?: $password = bcrypt('secret'),
        \App\Models\User::$ROLE => str_random(10),
    ];
});

$factory->define(App\Models\Content::class, function(Faker\Generator $faker): array {
    $title = $faker->sentence;

    return [
        \App\Models\Content::$TITLE => $title,
        \App\Models\Content::$SLUG => str_slug($title),
        \App\Models\Content::$CONTENT => $faker->paragraphs(3, true),
        \App\Models\Content::$STATUS => $faker->word,
        \App\Models\Content::$THUMBNAIL => $faker->url
    ];
});