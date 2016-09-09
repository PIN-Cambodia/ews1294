<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVillageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('village', function (Blueprint $table) {
            $table->increments('VCode', 10)->default('0')->comment('Village Code');
            $table->integer('prefix', 10)->unsigned()->comment('1:Khum-Srok-Khet, 2:Sangkat-Khan-ReachTheany');
            $table->string('VName_en', 50)->nullable();
            $table->string('VName_kh', 50)->nullable();
            $table->integer('CCode',10 )->unsigned()->comment('Commune Code');
            $table->date('modified_date');
            $table->integer('modified_by',10)->unsigned();
            $table->tinyInteger('VStatus',1)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('village');
    }
}
