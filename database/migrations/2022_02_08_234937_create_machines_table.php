<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('machin_number')->unique();
            $table->string('asset_number')->nullable();
            $table->Integer('no_of_colour_front')->nullable();
            $table->Integer('no_of_colour_back')->nullable();            
            $table->float('rpm')->nullable();
            $table->boolean('isActive')->default(0);
            $table->Integer('department_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->Integer('machine_type_id')->unsigned();
            $table->foreign('machine_type_id')->references('id')->on('machine_types');
            $table->Integer('wheel_type_id')->unsigned();
            $table->foreign('wheel_type_id')->references('id')->on('wheel_types');
            $table->Integer('condition_id')->unsigned();
            $table->foreign('condition_id')->references('id')->on('conditions');
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
        Schema::dropIfExists('machines');
    }
}
