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

            factory(\App\Models\Content::class, 5)->make()->each(function(\App\Models\Content $content) use ($user) {

                $faker = \Faker\Factory::create();

                $content[\App\Models\Content::$CONTENT] = $faker->sentence(20);
                $content[\App\Models\Content::$TYPE] = \App\Models\Content::FORMATION;

                $content = $user->contents()->save($content);

                factory(\App\Models\Chapter::class, 3)->make()->each(function(\App\Models\Chapter $chapter) use ($content) {
                    $content->chapters()->save($chapter);

                    $chapter->sources()->create([
                        'name' => array_random([
                            'video-1.mp4',
                            'video-2.mp4',
                            'video-3.mp4',
                            'video-4.mp4'
                        ]),
                        'type' => config('sources.type.VIDEO')
                    ]);

                    $chapter->sources()->create([
                        'name' => array_random([
                            'file-1.zip',
                            'file-2.zip',
                            'file-3.zip'
                        ])
                    ]);
                });
            });
        });
    }
}
