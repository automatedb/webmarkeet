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
        DB::table('contents')->delete();
        DB::table('users')->delete();

        $this->call(UserContentSeeder::class);
        $this->call(UserCustomerSeeder::class);
    }
}
