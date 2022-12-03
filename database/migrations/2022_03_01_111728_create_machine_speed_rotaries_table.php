<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachineSpeedRotariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_speed_rotaries', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('from_ord_qty');
            $table->Integer('to_ord_qty');  
            $table->float('batch_size_cat_ref')->nullable();           
            $table->float('metres')->nullable();
            $table->Integer('substrate_category_id')->unsigned()->nullable();
            $table->foreign('substrate_category_id')->references('id')->on('substrate_categories');
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
        Schema::dropIfExists('machine_speed_rotaries');
    }
}
