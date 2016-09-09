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
            $table->increments('CCode', 10)->default('0')->comment('Commune Code');
            $table->integer('prefix', 10)->unsigned()->nullable(false)->comment('1:Khum-Srok-Khet, 2:Sangkat-Khan-ReachTheany');
            $table->string('CName_en', 50)->nullable();
            $table->string('CName_kh', 50)->nullable();
            $table->integer('DCode', 10)->unsigned()->comment('District Code');
            $table->date('modified_date');
            $table->integer('modified_by', 10)->unsigned();
            $table->tinyInteger('status', 2)->unsigned();
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
