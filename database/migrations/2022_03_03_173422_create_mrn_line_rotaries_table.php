<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMrnLineRotariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mrn_line_rotaries', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('mrn_header_rotary_id')->unsigned()->nullable();
            $table->foreign('mrn_header_rotary_id')->references('id')->on('mrn_header_rotaries');
            $table->Integer('work_order_header_id')->unsigned()->nullable();
            $table->foreign('work_order_header_id')->references('id')->on('work_order_headers'); 
            $table->string('workorder_no', 15);                       
            $table->string('size')->nullable();
            $table->string('quantity')->nullable();
            $table->float('theoretical_usage_mtr')->nullable();  
            $table->float('waste_mtr')->nullable();                      
            $table->float('line_useage_mtr')->nullable(); 
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
        Schema::dropIfExists('mrn_line_rotaries');
    }
}
