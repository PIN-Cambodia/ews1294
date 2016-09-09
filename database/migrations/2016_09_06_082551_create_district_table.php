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
            $table->increments('DCode', 10)->comment('District Code');
            $table->integer('prefix', 10)->default('1')->nullable(false)->comment('1:Khum-Srok-Khet, 2:Sangkat-Khan-ReachTheany');
            $table->string('DName_en', 28)->default('NULL');
            $table->string('DName_kh', 22)->default('NULL');
            $table->tinyInteger('PCode',3)->unsigned()->default('NULL')->comment('District Code');
            $table->date('modified_date')->nullable(false);
            $table->integer('modified_by',11)->nullable(false);
            $table->tinyInteger('status',2 )->nullable(false)->default('1')->comment('1:normal; 0:removed; -1:transferred');
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
