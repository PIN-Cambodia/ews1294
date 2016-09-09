<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvinceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('province', function (Blueprint $table) {
            $table->increments('PROCODE', 3);
            $table->integer('prefix', 10)->unsigned()->nullable(false)->comment('1:Khum-Srok-Khet, 2:Sangkat-Khan-ReachTheany');
            $table->string('PROVINCE', 20)->nullable();
            $table->string('PROVINCE_KH', 20)->nullable();
            $table->text('PReminderGroup')->nullable();
            $table->integer('CallFlowID',11)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('province');
    }
}
