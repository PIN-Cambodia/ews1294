<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensorlogs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sensor_id');
            $table->integer('stream_height');
            $table->tinyInteger('charging');
            $table->integer('voltage');
            $table->timestamp('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sensorlogs');
    }
}
