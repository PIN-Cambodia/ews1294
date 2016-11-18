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
        $check_existing_user_1 =  User::where('name','=', "Chris Sevilleja")->first();
        if(!$check_existing_user_1)
        {
            User::create(array(
                'name'     => 'Chris Sevilleja',
                'email'    => 'chris@scotch.io',
                'password' => Hash::make('awesome'),
                //'api_token' => str_random(60),
                'api_token' => 'ldanmxW3bk107k0T9ReWVPVlkdiF5KdvloUzQhSjOnyyivrWjmwTdJYlz5ly',
            ));
        }
        // Create authorize api_token user for retrieving callLog from Twilio IVR system
        $check_existing_user_2 = User::where('name','=', "Twilio user")->first();
        if(!$check_existing_user_2)
        {
            User::create(array(
                'name'     => 'Twilio user',
                'email'    => 'twilio@oi.org',
                'password' => Hash::make('tw_%*'),
                //'api_token' => str_random(60),
                'api_token' => 'tYW6Vp4Dez4lQ21a4UOwxMb3UNKeSb6QaCS2IDfA6TKSPpjOGWt2QsM0gCj3',
            ));
        }

        // Create authorize api_token user for receiving data from Sensor
        $check_existing_user_3 =  User::where('name','=', "sensor user")->first();
        if(!$check_existing_user_3)
        {
            User::create(array(
                'name'     => 'Sensor user',
                'email'    => 'sensor@oi.org.kh',
                'password' => Hash::make('S_*/or'),
                //'api_token' => str_random(60),
                'api_token' => 'mqZnfQBMa6WYgQWgScg6e3fIiYBjZIbWeqZ73DcEMu8WumHyqTQRJkofQMBK',
            ));
        }

    }
}
