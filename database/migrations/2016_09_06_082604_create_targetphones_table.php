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
            $table->increments('id', 11);
            $table->string('commune_code', 8)->nullable(false);
            $table->string('phone', 15)->nullable();
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
