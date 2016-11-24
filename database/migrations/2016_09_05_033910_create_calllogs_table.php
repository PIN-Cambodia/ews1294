<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalllogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calllogs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_id')->unsigned();
            $table->string('phone_number')->nullable();
            $table->tinyInteger('result')->unsigned()->comment('1=Completed; 2=Failed; 3=Busy; 4=No anwser;');
            $table->tinyInteger('duration')->unsigned();
            $table->tinyInteger('no_of_retries')->unsigned();
            $table->tinyInteger('project_id')->unsigned();
            $table->tinyInteger('call_flow_id')->unsigned();
            $table->timestamp('called_time');
            $table->tinyInteger('retry_time')->unsigned();
            $table->tinyInteger('max_retry')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('calllogs');
    }
}
