<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          User::create(array(
              'name'     => 'Chris Sevilleja',
              'email'    => 'chris@scotch.io',
              'password' => Hash::make('awesome'),
              'api_token' => str_random(60),
          ));
    }
}
