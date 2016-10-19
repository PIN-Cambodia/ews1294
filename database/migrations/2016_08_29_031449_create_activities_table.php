<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('activities', function (Blueprint $table) {
            $table->increments('activity_id');
            $table->integer('maunual_auto')->unsigned()->comment('1:Manual; 2:Automatic');
            $table->integer('user_id')->unsigned();
            $table->text('list_commune_codes');
            $table->integer('no_of_phones_called');
            $table->tinyInteger('no_of_retry');
            $table->tinyInteger('retry_time');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('activities');
    }
}
