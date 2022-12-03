<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCylinderMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cylinder_machine', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('machine_id')->unsigned()->nullable();
            $table->foreign('machine_id')->references('id')->on('machines');
            $table->Integer('cylinder_id')->unsigned()->nullable();
            $table->foreign('cylinder_id')->references('id')->on('cylinders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cylinder_machine');
    }
}
