<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packings', function (Blueprint $table) {
            $table->Integer('work_order_header_id')->unsigned();
            $table->foreign('work_order_header_id')->references('id')->on('work_order_headers');   
            $table->string('workorder_no', 15);
            $table->date('date');  
            $table->string('shift', 2);  
            $table->string('size', 55)->nullable();              
            $table->Integer('quantity')->nullable();
            $table->Integer('s_qty')->nullable(); 
            $table->Integer('produce_size_changes')->nullable();   
            $table->string('insert_type', 15)->nullable();  
            $table->Integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees'); 
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
        Schema::dropIfExists('packings');
    }
}
