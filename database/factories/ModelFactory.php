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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'role' => str_random(10),
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
