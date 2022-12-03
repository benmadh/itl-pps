<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkOrderHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_order_headers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('workorder_no', 15);            
            $table->Integer('main_workorder_id')->nullable();
            $table->string('main_workorder_no',15)->nullable();   
            $table->string('same_as_status',1)->nullable()->default('N');
            $table->string('same_as_felds')->nullable();
            $table->string('customer_order_no',75)->nullable();
            $table->string('po_number',75)->nullable();
            $table->string('sales_order_no',75)->nullable();
            $table->date('order_date')->nullable();
            $table->date('delivery_date');            
            $table->date('po_date')->nullable();
            $table->date('pcu_date')->nullable();
            $table->date('mwo_delivery_date')->nullable();
            $table->string('delivery_address', 200)->nullable();
            $table->float('wo_quantity')->nullable();
            $table->float('wo_value')->nullable();  
            $table->float('lenght')->nullable();
            $table->float('width')->nullable();
            $table->Integer('size_changes')->nullable();
            $table->Integer('no_of_colors_front')->nullable();
            $table->Integer('no_of_colors_back')->nullable();
            $table->string('print_colors',75)->nullable();
            $table->string('ground_shade',75)->nullable();
            $table->string('fold_type',75)->nullable();
            $table->string('material_type',75)->nullable();
            $table->string('hold_status',75)->nullable();
            $table->string('hold_reason')->nullable();
            $table->string('remarks')->nullable();
            $table->Integer('chain_id')->unsigned()->nullable();
            $table->foreign('chain_id')->references('id')->on('chains');
            $table->Integer('customer_group_id')->unsigned()->nullable();
            $table->foreign('customer_group_id')->references('id')->on('customer_groups');            
            $table->Integer('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->Integer('label_reference_id')->unsigned()->nullable();
            $table->foreign('label_reference_id')->references('id')->on('label_references'); 
            $table->Integer('department_id')->unsigned()->nullable();
            $table->foreign('department_id')->references('id')->on('departments'); 
            $table->Integer('order_type_id')->unsigned()->nullable();
            $table->foreign('order_type_id')->references('id')->on('order_types'); 
            $table->Integer('label_type_id')->unsigned()->nullable();
            $table->foreign('label_type_id')->references('id')->on('label_types');
            $table->integer('status_id')->unsigned()->nullable()->default('1');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->string('chain_id_lv',50)->nullable(); 
            $table->Integer('print_status_id')->unsigned()->nullable()->default('1');
            $table->foreign('print_status_id')->references('id')->on('statuses');
            $table->Integer('cutting_status_id')->unsigned()->nullable()->default('1');
            $table->foreign('cutting_status_id')->references('id')->on('statuses');
            $table->Integer('verifications_status_id')->unsigned()->nullable()->default('1');
            $table->foreign('verifications_status_id')->references('id')->on('statuses');
            $table->Integer('packing_status_id')->unsigned()->nullable()->default('1');
            $table->foreign('packing_status_id')->references('id')->on('statuses');
            $table->Integer('aql_status_id')->unsigned()->nullable()->default('1');
            $table->foreign('aql_status_id')->references('id')->on('statuses');
            $table->Integer('despatch_status_id')->unsigned()->nullable()->default('1');
            $table->foreign('despatch_status_id')->references('id')->on('statuses');
            $table->Integer('mrn_print_status_id')->unsigned()->nullable()->default('1');
            $table->foreign('mrn_print_status_id')->references('id')->on('statuses');
            $table->Integer('mrn_issue_status_id')->unsigned()->nullable()->default('1');
            $table->foreign('mrn_issue_status_id')->references('id')->on('statuses');
            $table->Integer('cutting_method_id')->unsigned()->nullable();
            $table->foreign('cutting_method_id')->references('id')->on('cutting_methods');
            $table->string('size_id', 75)->nullable();
            $table->string('line_item', 50)->nullable();     
            $table->string('item_code',75)->nullable();           
            $table->string('style_no', 75)->nullable();            
            $table->string('prd_image', 100)->nullable();
            $table->string('prd_type', 100)->nullable(); 
            $table->string('prd_code', 100)->nullable(); 
            $table->string('prd_description', 155)->nullable();            
            $table->string('wo_quality', 100)->nullable();
            $table->string('wo_collection', 100)->nullable();
            $table->string('vsd', 75)->nullable(); 
            $table->string('vss', 75)->nullable(); 
            $table->string('rn', 75)->nullable(); 
            $table->string('ca', 75)->nullable(); 
            $table->string('dpci', 75)->nullable();            
            $table->string('contract_code', 75)->nullable();
            $table->string('ms_dept', 75)->nullable(); 
            $table->string('series_no', 75)->nullable(); 
            $table->string('stroke_no', 75)->nullable();           
            $table->string('supplier_code', 75)->nullable(); 
            $table->string('style_descr', 155)->nullable(); 
            $table->string('po_code', 75)->nullable(); 
            $table->string('department', 75)->nullable(); 
            $table->string('country_code', 75)->nullable(); 
            $table->string('country_of_origin', 100)->nullable(); 
            $table->string('factory_id', 75)->nullable(); 
            $table->string('date_of_mrf', 75)->nullable();
            $table->string('silhouette', 75)->nullable();  
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
        Schema::dropIfExists('work_order_headers');
    }
}
