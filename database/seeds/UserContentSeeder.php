<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contents')->delete();
        DB::table('users')->delete();

        factory(App\Models\User::class)->create()->each(function($user) {
            factory(\App\Models\Content::class, 20)->make()->each(function($content) use ($user) {
                $user->contents()->save($content);
            });
        });
    }
}
