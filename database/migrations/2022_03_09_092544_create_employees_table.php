<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('epf_no')->unique();
            $table->string('full_name',100);           
            $table->string('call_name', 50);
            $table->string('gender', 50);
            $table->string('contact_no', 50);             
            $table->string('shift', 2);   
            $table->boolean('activation')->default(0);
            $table->string('photo', 150);
            $table->Integer('department_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('departments'); 
            $table->Integer('designation_id')->unsigned();
            $table->foreign('designation_id')->references('id')->on('designations');
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
        Schema::dropIfExists('employees');
    }
}
