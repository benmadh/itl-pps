<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuttingWasteRotariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cutting_waste_rotaries', function (Blueprint $table) {
            $table->increments('id');           
            $table->Integer('pcs'); 
            $table->Integer('cutting_methods_id')->unsigned()->nullable();
            $table->foreign('cutting_methods_id')->references('id')->on('cutting_methods');
            $table->Integer('mrn_rate_file_rotaries_id')->unsigned()->nullable();
            $table->foreign('mrn_rate_file_rotaries_id')->references('id')->on('mrn_rate_file_rotaries');
            $table->Integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->Integer('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->Integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cutting_waste_rotaries');
    }
}
