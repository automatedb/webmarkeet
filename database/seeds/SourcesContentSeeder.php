<?php

use Illuminate\Database\Seeder;

class SourcesContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class, 'admin')->create()->each(function($user) {
            factory(\App\Models\Content::class, 10)->make()->each(function(\App\Models\Content $content) use ($user) {
                $user->contents()->save($content);

                $content->sources()->create([
                    'name' => array_random([
                        'file-1.zip',
                        'file-2.zip',
                        'file-3.zip'
                    ])
                ]);
            });

            factory(\App\Models\Content::class, 10)->make()->each(function(\App\Models\Content $content) use ($user) {
                $user->contents()->save($content);
            });
        });
    }
}
