@extends('templates.admin.layout')
@section('content')
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-11 col-sm-11 col-xs-11">
                            <h2>M R N For PFL Rotary</h2>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2> <a href="{{route('mrnRotary.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="#" data-parsley-validate class="form-horizontal form-label-left">
                        
                        <div class="row">
                            <div class="col-md-12"> 
                                    <div class="x_panel">
                                        <div class="form-group">
                                            <table width="55%" class="table table-bordered" id="table_bom" >
                                                <thead>
                                                    <tr> 
                                                        <th width="25%">Description</th>                                        
                                                        <th width="15%">Region</th> 
                                                        <th width="15%" >Benchmark</th>  
                                                    </tr>
                                                </thead>

                                                <tfoot>
                                                    <tr>
                                                        <th style="border: none;"></th>
                                                        <th style="border: none;"></th>
                                                        <th style="border: none;"></th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    @if (!empty($dataObjRegion))
                                                        <tr>                                                              
                                                            <td style="font-weight:bold;">Workorder #</td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjRegion->workorder_no}}" class="form-control" readonly></td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjBenchmark->workorder_no}}" class="form-control" readonly></td>
                                                        </tr>

                                                        <tr>                                                              
                                                            <td style="font-weight:bold;">Total Quantity</td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjRegion->total_quantity}}" class="form-control" readonly></td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjBenchmark->total_quantity}}" class="form-control" readonly></td>
                                                        </tr>

                                                         <tr>                                                              
                                                            <td style="font-weight:bold;">MRN Date</td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjRegion->date}}" class="form-control" readonly></td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjBenchmark->date}}" class="form-control" readonly></td>
                                                        </tr>

                                                        <tr>                                                              
                                                            <td>Batch Size Category Reference</td>
                                                            <td><input type="text" value="" class="form-control" readonly></td>
                                                            <td><input type="text" value="" class="form-control" readonly></td>
                                                        </tr> 

                                                        <tr>                                                              
                                                            <td>Material Type</td>
                                                            <td><input type="text" value="" class="form-control" readonly></td>
                                                            <td><input type="text" value="" class="form-control" readonly></td>
                                                        </tr>
                                                          
                                                        <tr>                                                              
                                                            <td>Fold Type</td>
                                                            <td><input type="text" value="{{$dataObjRegion->cutting_methods->name}}" class="form-control" readonly></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->cutting_methods->name}}" class="form-control" readonly></td>
                                                        </tr> 
                                                        
                                                        <tr>                                                              
                                                            <td>Label Length</td>
                                                            <td><input type="text" value="{{$dataObjRegion->work_order_headers->lenght}}" class="form-control" readonly></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->work_order_headers->lenght}}" class="form-control" readonly></td>
                                                        </tr> 

                                                        <tr>                                                              
                                                            <td>Label Width</td>
                                                            <td><input type="text" value="{{$dataObjRegion->work_order_headers->width}}" class="form-control" readonly></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->work_order_headers->width}}" class="form-control" readonly></td>
                                                        </tr> 

                                                        <tr>                                                              
                                                            <td>No of Size Changes</td>
                                                            <td><input type="text" value="{{$dataObjRegion->work_order_headers->size_changes}}" class="form-control" readonly></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->work_order_headers->size_changes}}" class="form-control" readonly></td>
                                                        </tr>

                                                        <tr>                                                              
                                                            <td>No of Colors front</td>
                                                            <td><input type="text" value="{{$dataObjRegion->work_order_headers->no_of_colors_front}}" class="form-control" readonly></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->work_order_headers->no_of_colors_front}}" class="form-control" readonly></td>
                                                        </tr> 

                                                        <tr>                                                              
                                                            <td>No of Colors Back</td>
                                                            <td><input type="text" value="{{$dataObjRegion->work_order_headers->no_of_colors_back}}" class="form-control" readonly></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->work_order_headers->no_of_colors_back}}" class="form-control" readonly></td>
                                                        </tr>

                                                        <tr>                                                              
                                                            <td>Size Changes After Batching</td>
                                                            <td><input type="text" value="{{$dataObjRegion->size_changes_after_batching}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->size_changes_after_batching}}" class="form-control" disabled></td>
                                                        </tr>   

                                                        <tr>                                                              
                                                            <td>Machine Type</td>
                                                            <td><input type="text" value="{{$dataObjRegion->machine_types->name}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->machine_types->name}}" class="form-control" disabled></td>
                                                        </tr> 

                                                        <tr>                                                              
                                                            <td>Duration per reference change (min)</td>
                                                            <td><input type="text" value="{{$dataObjRegion->ref_changes_min}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->ref_changes_min}}" class="form-control" disabled></td>
                                                        </tr> 

                                                         <tr>                                                              
                                                            <td>Running Waste (%)</td>
                                                            <td><input type="text" value="{{$dataObjRegion->running_waste_percentage}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->running_waste_percentage}}" class="form-control" disabled></td>
                                                        </tr>  

                                                         <tr>                                                              
                                                            <td>C&F Waste</td>
                                                            <td><input type="text" value="{{$dataObjRegion->cf_waste_pcs}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->cf_waste_pcs}}" class="form-control" disabled></td>
                                                        </tr>  

                                                        <tr>                                                              
                                                            <td>Additionals to customers  (%)</td>
                                                            <td><input type="text" value="{{$dataObjRegion->add_to_customer_percentage}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->add_to_customer_percentage}}" class="form-control" disabled></td>
                                                        </tr>  

                                                        <tr>                                                              
                                                            <td>Machine Speed</td>
                                                            <td><input type="text" value="{{$dataObjRegion->machine_speed_mrt_per_hrs}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->machine_speed_mrt_per_hrs}}" class="form-control" disabled></td>
                                                        </tr>

                                                        <tr>                                                              
                                                            <td>No of Tapes</td>
                                                            <td><input type="text" value="{{$dataObjRegion->no_of_tapes}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->no_of_tapes}}" class="form-control" disabled></td>
                                                        </tr>

                                                        <tr>                                                              
                                                            <td>Setup time per tape</td>
                                                            <td><input type="text" value="{{$dataObjRegion->setup_time_per_tape}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->setup_time_per_tape}}" class="form-control" disabled></td>
                                                        </tr>

                                                        <tr>                                                              
                                                            <td>Setup Time per Colour (Front)</td>
                                                            <td><input type="text" value="{{$dataObjRegion->setup_time_per_colour_front}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->setup_time_per_colour_front}}" class="form-control" disabled></td>
                                                        </tr>  

                                                        <tr>                                                              
                                                            <td>Setup Time per Colour (Back)</td>
                                                            <td><input type="text" value="{{$dataObjRegion->setup_time_per_colour_back}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->setup_time_per_colour_back}}" class="form-control" disabled></td>
                                                        </tr>

                                                        <tr>                                                              
                                                            <td>Time per plate/screen change</td>
                                                            <td><input type="text" value="{{$dataObjRegion->setup_time_per_colour_back}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->setup_time_per_colour_back}}" class="form-control" disabled></td>
                                                        </tr>

                                                        <tr>                                                              
                                                            <td>Qty per Size</td>
                                                            <td><input type="text" value="{{$dataObjRegion->qty_per_Size}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->qty_per_Size}}" class="form-control" disabled></td>
                                                        </tr>

                                                        <tr>                                                              
                                                            <td>Qty per Size (Packing)</td>
                                                            <td><input type="text" value="{{$dataObjRegion->qty_per_Size_packing}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->qty_per_Size_packing}}" class="form-control" disabled></td>
                                                        </tr>

                                                         <tr>                                                              
                                                            <td>Total Time Setup time duration</td>
                                                            <td><input type="text" value="{{$dataObjRegion->total_time_setup_time_duration}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->total_time_setup_time_duration}}" class="form-control" disabled></td>
                                                        </tr>

                                                         <tr>                                                              
                                                            <td>Total time for plate/screen changes</td>
                                                            <td><input type="text" value="{{$dataObjRegion->total_time_for_plate_changes}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->total_time_for_plate_changes}}" class="form-control" disabled></td>
                                                        </tr>

                                                         <tr>                                                              
                                                            <td>Running Time</td>
                                                            <td><input type="text" value="{{$dataObjRegion->cal_running_time}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->cal_running_time}}" class="form-control" disabled></td>
                                                        </tr>

                                                         <tr>                                                              
                                                            <td>Total Standard time for the job</td>
                                                            <td><input type="text" value="{{$dataObjRegion->total_standard_time_for_job}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->total_standard_time_for_job}}" class="form-control" disabled></td>
                                                        </tr>

                                                         <tr>                                                              
                                                            <td>Actual Time to Complete the Job</td>
                                                            <td><input type="text" value="{{$dataObjRegion->actual_time_to_Complete_job}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->actual_time_to_Complete_job}}" class="form-control" disabled></td>
                                                        </tr>

                                                         <tr>                                                              
                                                            <td>Total Material Issued for the job</td>
                                                            <td><input type="text" value="{{$dataObjRegion->total_material_issued_mtr}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->total_material_issued_mtr}}" class="form-control" disabled></td>
                                                        </tr>

                                                         <tr>                                                              
                                                            <td>Total QTY to Produce (with wastage) </td>
                                                            <td><input type="text" value="{{$dataObjRegion->total_qty_to_produce_with_wastage}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->total_qty_to_produce_with_wastage}}" class="form-control" disabled></td>
                                                        </tr>

                                                         <tr>                                                              
                                                            <td>PRODUCTIVITY CALC - Hrs </td>
                                                            <td><input type="text" value="{{$dataObjRegion->productivity_calc_hrs}}" class="form-control" disabled></td>
                                                            <td><input type="text" value="{{$dataObjBenchmark->productivity_calc_hrs}}" class="form-control" disabled></td>
                                                        </tr>

                                                        <tr>                                                              
                                                            <td style="font-weight:bold;">OEE (%) </td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjRegion->cal_oee_percentage}}%" class="form-control" disabled></td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjBenchmark->cal_oee_percentage}}%" class="form-control" disabled></td>
                                                        </tr>

                                                        <tr>                                                              
                                                            <td style="font-weight:bold;">Performance (%)</td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjRegion->cal_performance_percentage}}%" class="form-control" disabled></td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjBenchmark->cal_performance_percentage}}%" class="form-control" disabled></td>
                                                        </tr>

                                                        <tr>                                                              
                                                            <td style="font-weight:bold;">Quality (%)</td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjRegion->cal_quality_percentage}}%" class="form-control" disabled></td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjBenchmark->cal_quality_percentage}}%" class="form-control" disabled></td>
                                                        </tr>

                                                         <tr>                                                              
                                                            <td style="font-weight:bold;">Availability (%)</td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjRegion->cal_availability_percentage}}%" class="form-control" disabled></td>
                                                            <td style="font-weight:bold;"><input type="text" value="{{$dataObjBenchmark->cal_availability_percentage}}%" class="form-control" disabled></td>
                                                        </tr>
                                                        

                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop