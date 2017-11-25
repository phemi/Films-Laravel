<?php

use Illuminate\Database\Seeder;

class TablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //create 3 users
        //Illuminate\Support\Facades\DB::table('users')->delete();
        factory(App\Models\User::class, 3)->create();
        factory(\App\Models\Country::class, 50)->create();
        factory(\App\Models\Genre::class, 10)->create();
        factory(\App\Models\Comment::class, 3)->create();

        //assign each film to a genre
        \App\Models\Film::all()->each(function($film) {
            factory(\App\Models\FilmGenres::class, 1)->create(['film_id'=>$film->id]);
        });
    }
}
