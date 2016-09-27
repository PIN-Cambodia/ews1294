<?php

use Illuminate\Database\Seeder;
use App\Models\targetphones;

class TargetPhonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //$phones = array("010 567 487", "089 737 630", "012 628 979", "012 959 466", "011 676 331");
      $phones = array("017 696 365", "012 415 734");
      for($i=0;$i<sizeof($phones);$i++)
      {
        targetphones::create(array(
            'commune_code'     => '150107',
            'phone'    => $phones[$i],
        ));
      }

    }
}
