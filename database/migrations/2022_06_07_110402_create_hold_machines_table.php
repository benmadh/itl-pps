<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoldMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hold_machines', function (Blueprint $table) {
            $table->increments('id');            
            $table->date('date'); 
            $table->string('shift', 10);            
            $table->Integer('mc_hold_reason_id')->unsigned();
            $table->foreign('mc_hold_reason_id')->references('id')->on('mc_hold_reasons');
            $table->Integer('machine_id')->unsigned();
            $table->foreign('machine_id')->references('id')->on('machines'); 
            $table->Integer('department_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('departments');
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
        Schema::dropIfExists('hold_machines');
    }
}
