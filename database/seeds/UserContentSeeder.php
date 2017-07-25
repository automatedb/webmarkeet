<?php

use Illuminate\Database\Seeder;

class UserContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class, 'admin')->create()->each(function($user) {
            factory(\App\Models\Content::class, 20)->make()->each(function($content) use ($user) {
                $user->contents()->save($content);
            });
        });
    }
}
