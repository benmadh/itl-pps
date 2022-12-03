<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkOrderLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_order_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('work_order_header_id')->unsigned()->nullable();
            $table->foreign('work_order_header_id')->references('id')->on('work_order_headers');
            $table->string('workorder_no', 15); 
            $table->string('line_no', 55)->nullable();
            $table->string('size', 55)->nullable();              
            $table->Integer('quantity')->nullable();
            $table->string('barcode', 75)->nullable();
            $table->string('article', 75)->nullable();
            $table->string('price', 75)->nullable();
            $table->string('upos_required', 75)->nullable();
            $table->string('product_grp', 100)->nullable();
            $table->string('company_chain', 100)->nullable();
            $table->string('year_season', 100)->nullable();
            $table->string('source_code', 100)->nullable();
            $table->string('chest_literal', 100)->nullable();
            $table->string('new_stock_room_id', 100)->nullable();
            $table->string('wo_height', 100)->nullable();
            $table->string('european_size', 100)->nullable();
            $table->string('dept_code', 100)->nullable();
            $table->string('style_code', 100)->nullable();
            $table->string('colour_descr', 100)->nullable();
            $table->string('colour_code', 100)->nullable();
            $table->string('sku', 100)->nullable();
            $table->string('description_print_label', 100)->nullable();
            $table->string('garment_colour', 100)->nullable();
            $table->string('upc_no', 100)->nullable();
            $table->string('pack_qty', 100)->nullable();
            $table->string('date_code', 100)->nullable();
            $table->string('cup', 100)->nullable();
            $table->string('pack_code', 100)->nullable();
            $table->string('pack_ean_code', 100)->nullable();   
            $table->integer('status_id')->unsigned()->nullable()->default('1');
            $table->foreign('status_id')->references('id')->on('statuses'); 
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
        Schema::dropIfExists('work_order_lines');
    }
}
