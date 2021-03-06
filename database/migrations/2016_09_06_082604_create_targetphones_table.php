<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetphonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('targetphones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('commune_code');
            $table->string('phone')->nullable();
            $table->timestamp('updated_ate');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('targetphones');
    }
}
