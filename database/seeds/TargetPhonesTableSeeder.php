<?php

use Illuminate\Database\Seeder;

class TargetPhonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $phones = array("010 567 487", "089 737 630", "012 628 979", "012 959 466", "011 676 331");
      for($i=0;$i<sizeof($phones);$i++)
      {
        User::create(array(
            'commune_code'     => '150107',
            'phone'    => $phones[$i],
        ));
      }

    }
}
