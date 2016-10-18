<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district', function (Blueprint $table) {
            $table->increments('DCode')->comment('District Code');
            $table->integer('prefix')->comment('1:Khum-Srok-Khet, 2:Sangkat-Khan-ReachTheany');
            $table->string('DName_en')->default('NULL');
            $table->string('DName_kh')->default('NULL');
            $table->tinyInteger('PCode')->unsigned()->comment('District Code');
            $table->date('modified_date');
            $table->integer('modified_by');
            $table->tinyInteger('status')->default('1')->comment('1:normal; 0:removed; -1:transferred');
            $table->text('DReminderGroup')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('district');
    }
}
