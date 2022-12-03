<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMrnRateFileRotariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mrn_rate_file_rotaries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table_type');
            $table->boolean('isActive')->default(0);
            $table->Integer('each_clr_front_waste_mtr')->unsigned()->nullable();
            $table->Integer('each_clr_front_setup_time_min')->unsigned()->nullable();
            $table->Integer('each_clr_back_waste_mtr')->unsigned()->nullable();
            $table->Integer('each_clr_back_setup_time_min')->unsigned()->nullable();            
            $table->Integer('plate_change_waste_mtr')->unsigned()->nullable();
            $table->Integer('plate_change_setup_time_min')->unsigned()->nullable();
            $table->Integer('tape_setup_waste_mtr')->unsigned()->nullable();
            $table->Integer('tape_setup_setup_time_min')->unsigned()->nullable();
            $table->Integer('reference_change_time_min')->unsigned()->nullable();
            $table->Integer('machine_type_id')->unsigned();
            $table->foreign('machine_type_id')->references('id')->on('machine_types');
            $table->Integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->Integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations');
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
        Schema::dropIfExists('mrn_rate_file_rotaries');
    }
}
