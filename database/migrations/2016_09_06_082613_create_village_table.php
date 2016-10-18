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
            $table->increments('VCode')->comment('Village Code');
            $table->integer('prefix')->unsigned()->comment('1:Khum-Srok-Khet, 2:Sangkat-Khan-ReachTheany');
            $table->string('VName_en')->nullable();
            $table->string('VName_kh')->nullable();
            $table->integer('CCode')->unsigned()->comment('Commune Code');
            $table->date('modified_date');
            $table->integer('modified_by')->unsigned();
            $table->tinyInteger('VStatus')->unsigned();
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
