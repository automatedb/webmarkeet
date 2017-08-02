<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contains_sources')->delete();
        DB::table('sources')->delete();
        DB::table('contents')->delete();
        DB::table('users')->delete();

        $this->call(UserCustomerSeeder::class);
        $this->call(SourcesContentSeeder::class);
    }
}
