<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

//define factory settings to populate country
$factory->define(App\Models\Country::class, function (Faker $faker) {

    return [
        'country_name' => $faker->country
    ];
});

//define factory settings to populate genre
$factory->define(App\Models\Genre::class, function (Faker $faker) {

    return [
        'genre' => $faker->text(50)
    ];
});

//define factory settings to populate film
$factory->define(App\Models\Film::class, function (Faker $faker) {

    return [
        'name' => $faker->text(100),
        'slug' => $faker->unique()->slug,
        'description' => $faker->paragraph,
        'release_date' => $faker->date,
        'rating' => $faker->randomElement([1,2,3,4,5]),
        'price'=> $faker->randomNumber(5),
        'country_id' => \App\Models\Country::all()->random()->id,
        'photo' => 'film.jpg'
    ];
});

//define factory settings to populate  film-genres
//a film can have several genres
$factory->define(App\Models\FilmGenres::class, function (Faker $faker)  {
    //assign film to a random genre
    static $filmId;

    return [
        'genre_id' => \App\Models\Genre::all()->random()->id,
        'film_id' => $filmId
    ];
});

//define factory settings to populate comment; also create Film
//a comment for every film created
$factory->define(App\Models\Comment::class, function (Faker $faker) {

    return [
        'name' => $faker->name,
        'comment' => $faker->paragraph,
        'user_id' => \App\Models\User::all()->random()->id,
        'film_id' => function () { return factory(App\Models\Film::class)->create()->id; }
    ];
});

