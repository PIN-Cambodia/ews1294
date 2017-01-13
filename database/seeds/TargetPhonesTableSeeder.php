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
      $phones = array("010567487", "089737630", "012628979", "012959466", "011676331","017696365","012415734");
      for($i=0;$i<sizeof($phones);$i++)
      {
        targetphones::create(array(
            'commune_code'     => '150107',
            'phone'    => $phones[$i],
        ));
      }

    }
}
