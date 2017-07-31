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

/**
 * Permet de définir un adminstrateur en base de données
 */
$factory->defineAs(\App\Models\User::class, 'admin', function($faker) use ($factory) {
    $user1 = $factory->raw(\App\Models\User::class);

    return array_merge($user1, [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => 'john.doe@domain.tld',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);
});

/**
 * Permet de définir un adminstrateur en base de données
 */
$factory->defineAs(\App\Models\User::class, 'userdeleted', function($faker) use ($factory) {
    $user1 = $factory->raw(\App\Models\User::class);

    return array_merge($user1, [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => 'userdeleted@domain.tld',
        'password' => bcrypt('password'),
        'role' => 'customer'
    ]);
});

$factory->define(App\Models\Content::class, function(Faker\Generator $faker): array {
    $title = $faker->sentence;

    return [
        \App\Models\Content::$TITLE => $title,
        \App\Models\Content::$SLUG => str_slug($title),
        \App\Models\Content::$CONTENT => $faker->paragraphs(3, true),
        \App\Models\Content::$STATUS => array_random(array_keys(config('content.status'))),
        \App\Models\Content::$TYPE => array_random(array_keys(config('content.type'))),
        \App\Models\Content::$THUMBNAIL => array_random([
            '01.jpg',
            '02.jpg',
            '03.jpg'
        ])
    ];
});
