<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorTriggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensortriggers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sensor_id');
            $table->integer('level_warning');
            $table->integer('level_emergency');
            $table->text('affected_communes')->comment('list of affected communes to be called');
            $table->text('phone_numbers')->comment('(officers) phone numbers to be called for warning level');
            $table->string('sound_file')->comment('Sound file to be displayed');
            $table->text('emails_list')->comment('List of emails to be contact');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sensortriggers');
    }
}
