<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommuneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commune', function (Blueprint $table) {
            $table->increments('CCode')->comment('Commune Code');
            $table->integer('prefix')->unsigned()->comment('1:Khum-Srok-Khet, 2:Sangkat-Khan-ReachTheany');
            $table->string('CName_en')->nullable();
            $table->string('CName_kh')->nullable();
            $table->integer('DCode')->unsigned()->comment('District Code');
            $table->date('modified_date');
            $table->integer('modified_by')->unsigned();
            $table->tinyInteger('status')->unsigned();
            $table->text('CReminderGroup')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('commune');
    }
}
