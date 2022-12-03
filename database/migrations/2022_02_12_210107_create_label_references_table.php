<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabelReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('label_references', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('ground_colour')->nullable();
            $table->string('print_colour')->nullable();
            $table->string('combo')->nullable();            
            $table->Integer('default_lenght')->nullable();
            $table->Integer('default_width')->nullable(); 
            $table->string('file_name')->nullable(); 
            $table->Integer('chain_id')->unsigned()->nullable();
            $table->foreign('chain_id')->references('id')->on('chains');
            $table->Integer('labeltype_id')->unsigned()->nullable();
            $table->foreign('labeltype_id')->references('id')->on('label_types'); 
            $table->Integer('department_id')->unsigned()->nullable();
            $table->foreign('department_id')->references('id')->on('departments');                    
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
        Schema::dropIfExists('label_references');
    }
}
