<?php

use Illuminate\Database\Seeder;

class UserCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class, 'userdeleted')->create();
    }
}
