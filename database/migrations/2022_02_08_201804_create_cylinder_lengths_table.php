<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCylinderLengthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cylinder_length', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('cylinder_id')->unsigned()->nullable();
            $table->foreign('cylinder_id')->references('id')->on('cylinders');
            $table->Integer('length_id')->unsigned()->nullable();
            $table->foreign('length_id')->references('id')->on('lengths');    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cylinder_length');
    }
}
