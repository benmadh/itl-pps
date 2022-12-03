<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlnBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pln_boards', function (Blueprint $table) {
            $table->increments('id');
            $table->date('pln_date'); 
            $table->string('pln_shift')->nullable();
            $table->Integer('pln_mnt');                      
            $table->Integer('pln_qty')->nullable();
            $table->Integer('wo_tot_mnt')->nullable();                        
            $table->Integer('wo_tot_qty')->nullable();
            $table->Integer('machine_id')->unsigned();
            $table->foreign('machine_id')->references('id')->on('machines'); 
            $table->Integer('work_order_header_id')->unsigned();
            $table->foreign('work_order_header_id')->references('id')->on('work_order_headers');           
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
        Schema::dropIfExists('pln_boards');
    }
}
