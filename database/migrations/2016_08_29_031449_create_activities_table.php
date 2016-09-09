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
            $table->integer('maunual_auto')->unsigned()->comment('1:Manual; 2:Automatic')->nullable(false);
            $table->integer('user_id', 10)->unsigned()->nullable(false);
            $table->text('list_commune_codes')->nullable(false);
            $table->integer('no_of_phones_called', 11)->nullable(false);
            $table->tinyInteger('no_of_retry', 1)->nullable(false);
            $table->tinyInteger('retry_time', 5)->nullable(false);
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
