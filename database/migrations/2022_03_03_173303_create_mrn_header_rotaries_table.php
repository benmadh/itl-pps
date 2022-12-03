<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMrnHeaderRotariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mrn_header_rotaries', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('work_order_header_id')->unsigned();
            $table->foreign('work_order_header_id')->references('id')->on('work_order_headers');   
            $table->string('workorder_no', 15);
            $table->float('total_quantity');   
            $table->date('date');     
            $table->Integer('size_changes_after_batching')->nullable();    
            $table->Integer('machine_type_id')->unsigned();
            $table->foreign('machine_type_id')->references('id')->on('machine_types');
            $table->string('table_type',35);
            $table->Integer('cutting_method_id')->unsigned();
            $table->foreign('cutting_method_id')->references('id')->on('cutting_methods');            
            $table->Integer('ref_changes_min')->nullable();
            $table->Integer('setup_time_per_tape')->nullable();
            $table->Integer('setup_time_per_colour_front')->nullable();
            $table->Integer('setup_time_per_colour_back')->nullable();
            $table->Integer('setup_time_plate_change')->nullable();
            $table->float('setup_mtr_per_colour_front')->nullable();
            $table->float('setup_mtr_per_colour_back')->nullable();
            $table->float('setup_mtr_plate_change')->nullable();
            $table->float('running_waste_percentage')->nullable();
            $table->Integer('cf_waste_pcs')->nullable();
            $table->float('add_to_customer_percentage')->nullable();
            $table->float('machine_speed_mrt_per_hrs')->nullable();
            $table->Integer('no_of_tapes')->nullable();
            $table->float('cal_ribbon_mtr')->nullable();
            $table->float('cal_cut_fold_mtr')->nullable();
            $table->float('cal_set_up_mtr')->nullable();
            $table->float('cal_plate_change_mtr')->nullable();
            $table->float('cal_running_waste_mtr')->nullable();
            $table->Integer('qty_per_Size')->nullable();
            $table->Integer('qty_per_Size_packing')->nullable();
            $table->float('cal_additions_mtr')->nullable();
            $table->float('total_material_issued_mtr')->nullable();
            $table->Integer('total_time_setup_time_duration')->nullable();
            $table->Integer('total_time_for_plate_changes')->nullable();
            $table->Integer('cal_running_time')->nullable();
            $table->Integer('total_standard_time_for_job')->nullable();
            $table->Integer('total_qty_to_produce_with_wastage')->nullable();
            $table->Integer('productivity_calc_hrs')->nullable();
            $table->Integer('actual_time_to_Complete_job')->nullable();
            $table->float('cal_performance_percentage')->nullable();
            $table->float('cal_quality_percentage')->nullable();
            $table->float('cal_availability_percentage')->nullable();
            $table->float('cal_oee_percentage')->nullable();
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
        Schema::dropIfExists('mrn_header_rotaries');
    }
}
