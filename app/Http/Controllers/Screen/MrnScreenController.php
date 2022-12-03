<?php

namespace Larashop\Http\Controllers\Screen;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Carbon\Carbon;
use Response;
use Auth;
use Mail;
use Illuminate\Support\Facades\DB;
use TPDF;

Use Larashop\Http\Controllers\General\UtilityHelperGeneral;

use Larashop\Models\General\WorkOrderHeader;
use Larashop\Models\General\WorkOrderLine;
use Larashop\Models\General\MachineType;
use Larashop\Models\General\LabelType;
use Larashop\Models\General\LabelReference;
use Larashop\Models\General\CuttingMethod;
use Larashop\Models\Rotary\MrnHeaderRotary;

class MrnScreenController extends Controller
{
    use UtilityHelperGeneral;

    public function __construct(){
        $this->middleware('permission:mrn_screen_access_allow');       
    }

    public function index(){  
        $dep_id=13;
        $dataObjWo = WorkOrderHeader::where('deleted_at', '=', null)
                                     ->select('main_workorder_id','main_workorder_no')
                                     ->where('status_id', '=', 1)
                                     ->where('print_status_id', '=', 1)                     
                                     ->where('mrn_print_status_id', '=',1) 
                                     ->where('department_id', '=',  $dep_id)                                    
                                     ->groupBy('main_workorder_id')                                     
                                     ->get(); 

        $mctData = MachineType::where('deleted_at', '=', null)->get();
        $cuttingMethodData = CuttingMethod::where('deleted_at', '=', null)->get();
        $tableTypeLst = $this->genTableType(null); 

        $dataLst = MrnHeaderRotary::leftJoin('work_order_headers', function($join) {
                                      $join->on('mrn_header_rotaries.work_order_header_id', '=', 'work_order_headers.id');
                                    })
                                    ->where('mrn_header_rotaries.table_type', '=', 'Region')
                                    ->where('mrn_header_rotaries.deleted_at', '=', null)
                                    ->where('work_order_headers.department_id', '=', $dep_id)
                                    ->orderBy('mrn_header_rotaries.id', 'DESC')  
                                    ->limit(10)                                   
                                    ->get();

        $constraints = [
            'lenght' => null, 
            'width' => null, 
            'workorder_no' => null, 
            'main_workorder_id' => null, 
            'item_bom_status' => null, 
            'references_name' => null,  
            'fold_type'=>null                                 
        ]; 

        $params = [
            'title' => 'M R N For Screen',
            'searchingVals' => $constraints,
            'dataObjWo' => $dataObjWo,
            'mctData' => $mctData,
            'tableTypeLst' => $tableTypeLst,
            'cuttingMethodData' => $cuttingMethodData, 
            'dataLst' => $dataLst,
        ];
        
        return view('screen.mrnscreen.mrnscreen')->with($params);
    }


    public function store(Request $request){
        $dep_id=13;
        switch ($request->input('action')) {
            case 'print':
                $this->validate($request, [            
                    'main_workorder_id' => 'required', 
                    'machine_type_id' => 'required',
                    'fold_type' => 'required', 
                    'lenght' => 'required|numeric',
                    'width' => 'required|numeric', 
                    'no_front_colour' => 'required|numeric', 
                    'no_back_colour' => 'required|numeric',                 
                ]);

                $department_id=$dep_id;
                $company=Auth::user()->locations->companies->id;
                $location=Auth::user()->locations->id;

                $main_workorder_id=$request['main_workorder_id'];
                $lenght=$request['lenght'];
                $width=$request['width'];
                $status_id=$request['status_id']; 
                $machine_type_id=$request['machine_type_id'];        
                $fold_type=$request['fold_type'];

                $no_front_colour=(int)$request['no_front_colour'];
                $no_back_colour=(int)$request['no_back_colour'];
                $item_id=$request['item_id']; 
                $sbc_id=$request['sbc_id']; 
                $tableTypeLst = $this->genTableType(null); 
                $dataObjHeader = WorkOrderHeader::findOrFail($main_workorder_id);
                
                $woArrayList=$this->getWoArrayList($main_workorder_id);
                $total_qty=$this->getWoTotalQty($woArrayList); 

                $wo_h_id = $dataObjHeader->id;
                $main_workorder_no = $dataObjHeader->main_workorder_no;
                $label_reference_id = $dataObjHeader->label_reference_id;
                $chain_id = $dataObjHeader->chain_id;      
                $ref_name = $dataObjHeader->label_references->name; 
                $workorder_no = $dataObjHeader->workorder_no;
                $size_changes= $dataObjHeader->size_changes;
                $no_of_size= $dataObjHeader->size_changes+1;
                
                $size_array_list = array();
                $size_array = [];  
                $size_qty_array=[];
                $same_as="N";
                $j=0;
                $wohLst = WorkOrderHeader::where('main_workorder_id', '=', $wo_h_id)->get();
                foreach($wohLst  as $wohRow) {
                    $header_id=$wohRow->id; 
                    $input_02['lenght'] = $lenght;
                    $input_02['width'] = $width;
                    $input_02['no_of_colors_front'] = $no_front_colour;
                    $input_02['no_of_colors_back'] = $no_back_colour;
                    $input_02['updated_by'] = Auth::user()->id;
                    $input_02['updated_at'] = date('Y-m-d H:i:s');
                    $this->updateRecords('work_order_headers',array($header_id),$input_02);

                    $wlLst = WorkOrderLine::where('deleted_at', '=', null)                       
                            ->where('work_order_header_id',$header_id)                      
                            ->get();

                    foreach($wlLst  as $wlRow) {
                        $size_no=$wlRow->size;
                        $size_qty=$wlRow->quantity;
                        $size_qty_array[$j][$size_no]=$size_qty; 

                        if (!array_key_exists($size_no, $size_array)){
                            $size_array[$size_no]=0;                               
                            array_push($size_array_list, $size_no);
                        }               
                    } 
                    $j++;               
                }

                if($j>1){
                    $same_as="Y";
                }
                $sum = array();
                $size_cnt = array();
                foreach ($size_qty_array as $key => $sub_array) {
                    foreach ($sub_array as $sub_key => $value) {
                        //If array key doesn't exists then create and initize first before we add a value.
                        //Without this we will have an Undefined index error.
                        if( ! array_key_exists($sub_key, $sum)) $sum[$sub_key] = 0;
                        //Add Value
                        $sum[$sub_key]+=$value;

                        if( ! array_key_exists($sub_key, $size_cnt)) $size_cnt[$sub_key] = 0;
                        $size_cnt[$sub_key]+=1;
                    }
                }

                $total_no_of_size=0;
                foreach($size_cnt as $ke=>$va) {
                    $total_no_of_size+=$va;
                }
                $no_of_size_changes_after_batching=count($size_array_list);        
                
                $input_01['default_lenght'] = $lenght;  
                $input_01['default_width'] = $width;        
                $input_01['updated_by'] = Auth::user()->id;
                $input_01['updated_at'] = date('Y-m-d H:i:s');
                $this->updateRecords('label_references',array($label_reference_id),$input_01);  
                
                $ref_changes_min=0;
                $running_waste_percentage=0;
                $cf_waste_pcs=0;
                $add_to_customer_percentage=0;
                if($item_id){
                    if($lenght!=0){                
                        // Get mrn_rate_file ID
                        foreach($tableTypeLst  as $rowTt) {
                            $table_type=$rowTt;
                            $rate_file_id=$this->getRateFileIdRotary($company, $location, $machine_type_id, $table_type, $dep_id);
                            $dataObjRateFile = DB::table('mrn_rate_file_rotaries')
                                                ->where('id', '=', $rate_file_id)                    
                                                ->where('deleted_at', '=', null)                   
                                                ->first();
                            $ref_changes_min=0;$setup_time_per_tape=0;$setup_time_per_colour_front=0;
                            $setup_time_per_colour_back=0;$setup_time_plate_change=0;$setup_mtr_per_colour_front=0;
                            $setup_mtr_per_colour_back=0;$setup_mtr_plate_change=0;
                            $running_waste_percentage=0;$cf_waste_pcs=0;$add_to_customer_percentage=0;
                            $machine_speed_mrt_per_hrs=0;$no_of_tapes=0;$cal_ribbon_mtr=0;
                            $cal_cut_fold_mtr=0;$cal_set_up_mtr=0;$cal_plate_change_mtr=0;
                            $cal_running_waste_mtr=0;$qty_per_Size=0;$qty_per_Size_packing=0;
                            $cal_additions_mtr=0;$total_material_issued_mtr=0;$total_time_setup_time_duration=0;
                            $total_time_for_plate_changes=0;$cal_running_time=0;$total_standard_time_for_job=0;
                            $total_qty_to_produce_with_wastage=0;$productivity_calc_hrs=0;
                            $actual_time_to_Complete_job=0;$cal_performance=0;$cal_quality=0;
                            $cal_availability=0;$cal_oee=0;


                            if($dataObjRateFile ){
                                $ref_changes_min=$dataObjRateFile->reference_change_time_min;
                                $setup_time_per_tape=$dataObjRateFile->tape_setup_setup_time_min;
                                $setup_time_per_colour_front=$dataObjRateFile->each_clr_front_setup_time_min;
                                $setup_time_per_colour_back=$dataObjRateFile->each_clr_back_setup_time_min;
                                $setup_time_plate_change=$dataObjRateFile->plate_change_setup_time_min;
                                $setup_mtr_per_colour_front=$dataObjRateFile->each_clr_front_waste_mtr;
                                $setup_mtr_per_colour_back=$dataObjRateFile->each_clr_back_waste_mtr;
                                $setup_mtr_plate_change=$dataObjRateFile->plate_change_waste_mtr;
                            } 
                            //Get Running Waste (%)
                            $running_waste_percentage=$this->getRunningWasteRotary($rate_file_id, $total_qty, $sbc_id);
                            //Get C&F Waste               
                            $cf_waste_pcs=$this->getCfWasteRotary($rate_file_id, $fold_type);
                            //Get Additionals to customers (%)                
                            $add_to_customer_percentage=$this->getAddToCustomerRotary($rate_file_id, $total_qty);
                            //Get Machine Speed
                            $machine_speed_mrt_per_hrs=1000;
                            $no_of_tapes=1;
                            //calculations
                            $cal_ribbon_mtr=round(($total_qty*$lenght)/1000,2);
                            $cal_cut_fold_mtr=round(($cf_waste_pcs*$lenght)/1000,2);
                            $cal_set_up_mtr = ($setup_mtr_per_colour_front*$no_front_colour)+($setup_mtr_per_colour_back*$no_back_colour);
                            $cal_plate_change_mtr=(($no_of_size_changes_after_batching-1)*$setup_mtr_plate_change);
                            $cal_running_waste_mtr=round((($running_waste_percentage*$total_qty*$lenght)/1000)/100,2);
                            $qty_per_Size=round(($total_qty/$no_of_size_changes_after_batching));
                            $qty_per_Size_packing=round(($total_qty/$no_of_size_changes_after_batching));
                            $cal_additions_mtr=round((($lenght*$no_of_size_changes_after_batching*$add_to_customer_percentage*$qty_per_Size)/1000)/100,2);
                            $total_material_issued_mtr=$cal_ribbon_mtr+$cal_cut_fold_mtr+$cal_set_up_mtr+$cal_plate_change_mtr+$cal_running_waste_mtr+$cal_additions_mtr;
                            $total_time_setup_time_duration=$no_of_tapes+$setup_time_per_tape+$setup_time_per_colour_front+$setup_time_per_colour_back;
                            $total_time_for_plate_changes=(($no_of_size_changes_after_batching-1)*$setup_time_plate_change);
                            $cal_running_time=(($total_material_issued_mtr-$cal_set_up_mtr-$cal_plate_change_mtr)/($machine_speed_mrt_per_hrs/60)); 
                            $total_standard_time_for_job=$total_time_setup_time_duration+$total_time_for_plate_changes+$cal_running_time;
                            $total_qty_to_produce_with_wastage= ($total_material_issued_mtr/($lenght/1000));
                            $productivity_calc_hrs=($total_qty_to_produce_with_wastage/($total_standard_time_for_job/60));
                            $actual_time_to_Complete_job=1; //We need to be able to measure actual time
                            //$cal_performance=$total_standard_time_for_job/$actual_time_to_Complete_job;
                            $cal_performance=100;
                            $cal_quality= round(($cal_ribbon_mtr/$total_material_issued_mtr)*100,2);
                            $cal_availability=100;
                            $cal_oee=round(((($cal_performance*$cal_quality*$cal_availability)/100)/100),2);   

                            $mrn_headers_id=$this->insertRecords('mrn_header_rotaries',
                                        array('work_order_header_id'=>$wo_h_id,
                                                'workorder_no'=>$main_workorder_no,
                                                'total_quantity'=>$total_qty,
                                                'date'=>date('Y-m-d'),
                                                'machine_type_id'=>$machine_type_id,
                                                'size_changes_after_batching'=>$no_of_size_changes_after_batching,
                                                'table_type'=>$table_type,
                                                'cutting_method_id'=>$fold_type, 
                                                'ref_changes_min'=>$ref_changes_min,
                                                'setup_time_per_tape'=>$setup_time_per_tape,
                                                'setup_time_per_colour_front'=>$setup_time_per_colour_front,
                                                'setup_time_per_colour_back'=>$setup_time_per_colour_back,
                                                'setup_time_plate_change'=>$setup_time_plate_change,
                                                'setup_mtr_per_colour_front'=>$setup_mtr_per_colour_front,
                                                'setup_mtr_per_colour_back'=>$setup_mtr_per_colour_back,
                                                'setup_mtr_plate_change'=>$setup_mtr_plate_change,
                                                'running_waste_percentage'=>$running_waste_percentage,
                                                'cf_waste_pcs'=>$cf_waste_pcs,
                                                'add_to_customer_percentage'=>$add_to_customer_percentage,
                                                'machine_speed_mrt_per_hrs'=>$machine_speed_mrt_per_hrs,
                                                'no_of_tapes'=>$no_of_tapes,
                                                'cal_ribbon_mtr'=>$cal_ribbon_mtr,
                                                'cal_cut_fold_mtr'=>$cal_cut_fold_mtr,
                                                'cal_set_up_mtr'=>$cal_set_up_mtr,
                                                'cal_plate_change_mtr'=>$cal_plate_change_mtr,
                                                'cal_running_waste_mtr'=>$cal_running_waste_mtr,
                                                'qty_per_Size'=>$qty_per_Size,
                                                'qty_per_Size_packing'=>$qty_per_Size_packing,
                                                'cal_additions_mtr'=>$cal_additions_mtr,
                                                'total_material_issued_mtr'=>$total_material_issued_mtr,
                                                'total_time_setup_time_duration'=>$total_time_setup_time_duration,
                                                'total_time_for_plate_changes'=>$total_time_for_plate_changes,
                                                'cal_running_time'=>$cal_running_time,
                                                'total_standard_time_for_job'=>$total_standard_time_for_job,
                                                'total_qty_to_produce_with_wastage'=>$total_qty_to_produce_with_wastage,
                                                'productivity_calc_hrs'=>$productivity_calc_hrs,
                                                'actual_time_to_Complete_job'=>$actual_time_to_Complete_job,
                                                'cal_performance_percentage'=>$cal_performance,
                                                'cal_quality_percentage'=>$cal_quality,
                                                'cal_availability_percentage'=>$cal_availability,
                                                'cal_oee_percentage'=>$cal_oee,                                            
                                                'created_by'=>Auth::user()->id,
                                                'created_at' => date('Y-m-d H:i:s')),false);
                                                       
                            foreach($sum as $k=>$v) {
                                $size_no_ln=$k;
                                $size_qty_ln=$v;
                                $theoretical_usage_mtr=round(($cal_ribbon_mtr/$total_qty)*$size_qty_ln,2);
                                $line_useage_mtr=round(($total_material_issued_mtr/$total_qty)*$size_qty_ln,2);
                                $waste_mtr=$line_useage_mtr-$theoretical_usage_mtr;
                                $this->insertRecords('mrn_line_rotaries',
                                    array('mrn_header_rotary_id'=>$mrn_headers_id,
                                            'work_order_header_id'=> $wo_h_id,
                                            'workorder_no'=>$main_workorder_no,
                                            'size'=>$size_no_ln,
                                            'quantity'=>$size_qty_ln,
                                            'theoretical_usage_mtr'=>$theoretical_usage_mtr, 
                                            'waste_mtr'=>$waste_mtr,
                                            'line_useage_mtr'=>$line_useage_mtr,
                                            'created_by'=>Auth::user()->id,
                                            'created_at' => date('Y-m-d H:i:s')),false);
                            }

                            if($table_type=="Region"){
                                $departments_id=13; 
                                $tracking_dep="MRN";  
                                $tracking_des="MRN Created on MRN No : ".$mrn_headers_id." by : ".Auth::user()->name.", total material issued mtr : ".$total_material_issued_mtr;           
                                $ordertrackings = OrderTracking::create([
                                    'work_order_header_id' => $wo_h_id,
                                    'workorder_no' => $main_workorder_no,
                                    'date' => Carbon::now()->toDateString(),
                                    'department_id' => $departments_id,
                                    'tracking_dep'=> $tracking_dep,
                                    'tracking_des' => $tracking_des, 
                                    'company_id' =>Auth::user()->locations->companies->id,
                                    'location_id' => Auth::user()->locations->id, 
                                    'created_by'=>Auth::user()->id,           
                                ]);
                            } 

                        }   

                    }else{
                        dd("label lenght cant be zero");
                    }
                    
                }else{
                    dd("Item Not Define");
                }                
                
                $input_03['mrn_print_status_id'] = 2;
                $input_03['updated_by'] = Auth::user()->id;
                $input_03['updated_at'] = date('Y-m-d H:i:s');
                DB::table('work_order_headers')->where('main_workorder_id', $main_workorder_id)->update($input_03);

                $dataObjWo = WorkOrderHeader::where('deleted_at', '=', null)
                                             ->select('main_workorder_id','main_workorder_no')
                                             ->where('status_id', '=', 1)
                                             ->where('print_status_id', '=', 1)                     
                                             ->where('mrn_print_status_id', '=',1) 
                                             ->where('department_id', '=', $dep_id)
                                             ->groupBy('main_workorder_id')                                     
                                             ->get(); 

                $mctData = MachineType::where('deleted_at', '=', null)->get();
                $cuttingMethodData = CuttingMethod::where('deleted_at', '=', null)->get();
                $tableTypeLst = $this->genTableType(null); 

                
                $dataLst = MrnHeaderRotary::leftJoin('work_order_headers', function($join) {
                                      $join->on('mrn_header_rotaries.work_order_header_id', '=', 'work_order_headers.id');
                                    })
                                    ->where('mrn_header_rotaries.table_type', '=', 'Region')
                                    ->where('mrn_header_rotaries.deleted_at', '=', null)
                                    ->where('work_order_headers.department_id', '=', $dep_id)
                                    ->orderBy('mrn_header_rotaries.id', 'DESC')  
                                    ->limit(10)                                   
                                    ->get();

                $constraints = [
                    'lenght' => null, 
                    'width' => null, 
                    'workorder_no' => null, 
                    'main_workorder_id' => null, 
                    'item_bom_status' => null, 
                    'references_name' => null,  
                    'fold_type'=>null                                 
                ];       

                return view('screen/mrnscreen/mrnscreen', ['title' => 'M R N For Screen',
                                                            'searchingVals' => $constraints,
                                                            'dataObjWo' => $dataObjWo,
                                                            'mctData' => $mctData,
                                                            'tableTypeLst' => $tableTypeLst,
                                                            'cuttingMethodData' => $cuttingMethodData, 
                                                            'dataLst' => $dataLst]);             
                break;

            case 'search':
                $constraints = [
                    'wo_no' => $request['wo_no'],
                    'machine_type_id_search' => $request['machine_type_id_search'],
                    'table_type_search' => $request['table_type_search'],
                ];

                $dataLst = $this->doSearchingQuery($constraints);

                $mctData = MachineType::where('deleted_at', '=', null)->get();
                $cuttingMethodData = CuttingMethod::where('deleted_at', '=', null)->get();
                $tableTypeLst = $this->genTableType(null);
                $dataObjWo = WorkOrderHeader::where('deleted_at', '=', null)
                                             ->select('main_workorder_id','main_workorder_no')
                                             ->where('status_id', '=', 1)
                                             ->where('print_status_id', '=', 1)                     
                                             ->where('mrn_print_status_id', '=',1) 
                                             ->where('department_id', '=', $dep_id)
                                             ->groupBy('main_workorder_id')                                     
                                             ->get(); 
                $constraints_search = [
                    'lenght' => null, 
                    'width' => null, 
                    'workorder_no' => null, 
                    'main_workorder_id' => null, 
                    'item_bom_status' => null, 
                    'references_name' => null,  
                    'fold_type'=>null                                 
                ];
                return view('screen/mrnscreen/mrnscreen', ['dataLst' => $dataLst, 
                                                        'mctData' => $mctData, 
                                                        'dataObjWo' => $dataObjWo,
                                                        'searchingVals' => $constraints_search,
                                                        'cuttingMethodData' => $cuttingMethodData, 
                                                        'tableTypeLst' => $tableTypeLst]);
                break; 
        }
    }   

    public function edit($id) { 
        $table_type_r="Region";
        $table_type_b="Benchmark";
        $dataObjRegion = MrnHeaderRotary::where('deleted_at', '=', null)
                                         ->where('work_order_header_id', '=', $id) 
                                         ->where('table_type', '=', $table_type_r) 
                                         ->orderBy('id', 'desc')     
                                         ->first();

        $dataObjBenchmark = MrnHeaderRotary::where('deleted_at', '=', null)
                                         ->where('work_order_header_id', '=', $id) 
                                         ->where('table_type', '=', $table_type_b) 
                                         ->orderBy('id', 'desc')     
                                         ->first();
        
                      
        $params = [           
            'dataObjRegion' => $dataObjRegion,
            'dataObjBenchmark' => $dataObjBenchmark,
        ];
        return view('screen.mrnscreen.view')->with($params);
    }

    private function doSearchingQuery($constraints){  
        $dep_id=13; 
        $query = MrnHeaderRotary::leftJoin('work_order_headers', function($join) {
              $join->on('mrn_header_rotaries.work_order_header_id', '=', 'work_order_headers.id');
            });
        $query =  $query->where('mrn_header_rotaries.deleted_at', '=', null);
        $query =  $query->where('work_order_headers.department_id', '=', $dep_id)->orderBy('mrn_header_rotaries.id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {
                if ($fields[$index] == 'wo_no') {
                    if ($constraint != null) { 
                        $query = $query->where('mrn_header_rotaries.workorder_no', 'like', '%'.trim($constraint).'%');
                    } 
                }  
                if($fields[$index] == 'machine_type_id_search'){
                    if ($constraint != null) { 
                        $query = $query->where('mrn_header_rotaries.machine_type_id', '=', $constraint); 
                    }
                } 
                if($fields[$index] == 'table_type_search'){
                    if ($constraint != null) { 
                        $query = $query->where('mrn_header_rotaries.table_type', '=', $constraint); 
                    }
                }               
            $index++;
        }       
        return $query->paginate(100000);
    }

    public function print_mrn($id){
        TPDF::SetCreator("ITL");
        TPDF::SetAuthor('Nicola Asuni');
        TPDF::SetTitle('PPS');
        TPDF::SetSubject('TCPDF Tutorial');
        TPDF::SetKeywords('TCPDF, PDF, example, test, guide');
        
        //remove default header/footer
        TPDF::setPrintHeader(false);
        TPDF::setPrintFooter(false);  

        // Custom Header
        TPDF::setHeaderCallback(function($pdf){
              // Set font
              $pdf->SetFont('helvetica', 'B', 18);
              // Title
              $pdf->Cell(0, 15, '', 0, false, 'L', 0, '', 0, false, 'M', 'M'); 
        });

        // Custom Footer
        TPDF::setFooterCallback(function($pdf) {
                // Position at 15 mm from bottom
                $pdf->SetY(-15);
                // Set font
                $pdf->SetFont('helvetica', 'I', 8);
                // Page number
                $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages().' -- Print User :'.Auth::user()->name.' -- Print Date & Time : '.Carbon::now(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        });

        // // set default monospaced font
        TPDF::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // // set margins
        TPDF::SetMargins(0, 0, 0);
        
        // // set auto page breaks
        // TPDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // // set image scale factor
        TPDF::setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set margins
        TPDF::SetMargins(15, 8, 15);
        TPDF::SetHeaderMargin(PDF_MARGIN_HEADER);
        TPDF::SetFooterMargin(PDF_MARGIN_FOOTER);

        // // set auto page breaks
        // TPDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // // set font
        TPDF::SetFont('times', '', 8);

        TPDF::AddPage('P', 'A4');       
        // // disable existing columns
        TPDF::resetColumns();
        
        $html = '<style>
                    .tbl {
                        border-collapse: collapse; } 

                    .tbl th {
                        border: 1px solid #ccc;
                        padding: 10px;
                        text-align: left; } 

                    .tbl td {
                        border: 1px solid #ccc;
                        padding: 10px;
                        text-align: left; }

                    .tbl1 {
                        border-collapse: collapse; } 

                    .tbl1 th {
                        border: 0px solid #FFFFFF;
                        padding: 10px;
                        text-align: left; } 

                    .tbl1 td {
                        border: 0px solid #FFFFFF;
                        padding: 10px;
                        text-align: left; }

                    .tbl2 {
                        border-collapse: collapse; } 

                    .tbl2 th {
                        border: 1px solid #FFFFFF;
                        padding: 10px;
                        text-align: left;  } 

                    .tbl2 td {
                        border: 1px solid #FFFFFF;
                        padding: 10px;
                        text-align: left; }                    

                </style>';


        $dataObj = MrnHeaderRotary::where('deleted_at', '=', null)->where('id', '=', $id)->first();
        if($dataObj){
            $mrn_no=$dataObj->id;
            $work_order_header_id=$dataObj->work_order_header_id;
            $date=$dataObj->date;
            $workorder_no=$dataObj->workorder_no;
            $total_quantity=$dataObj->total_quantity;
            
            $dataObjWo = WorkOrderHeader::where('deleted_at', '=', null)->where('main_workorder_id', '=', $work_order_header_id)->get();
            $woh_des="";            
            $same_as_array =[];
            $k=0;
            foreach($dataObjWo  as $dataRow) {
                $wo_no=$dataRow->workorder_no;
                $wo_qty=$dataRow->wo_quantity;
                $woh_des.=$wo_no.'('.$wo_qty.') ';
                $same_as_array[$wo_no]=$wo_qty;
                $k++; 
            }   
            $same_as="N";
            if($k>1){
                $same_as="Y";
            }          
            
            $html .= '<table class="tbl3" width="100%" cellpadding="2">'; 
            $html .= '<tr>            
                        <td colspan="6" width="100%" align="right" ><h5>Production copy</h5></td>
                      </tr>'; 

            $html .= '<tr>            
                          <td colspan="2" width="33%" align="left" ><h1>Material Requisition Note</h1> </td>  
                          <td colspan="2" width="40%" style="vertical-align: bottom;" ><h1> # '.($mrn_no).'</h1></td>
                          <td colspan="2" width="27%" align="right" ><h2>Screen ('.($dataObj->table_type).') </h2></td>
                        </tr>';

            if($same_as=="Y"){
                $html .= '<tr>            
                          <td width="12%" align="left" ><h2>Main WO </h2> </td>  
                          <td width="28%" style="vertical-align: bottom;" ><h2> # '.($workorder_no).'</h2></td>
                          <td width="12%" align="left" ><h2>Total Qty </h2> </td>
                          <td width="24%" style="vertical-align: bottom;" ><h2> : '.($total_quantity).'</h2></td>
                          <td width="10%" align="left" ><h2>Date </h2> </td>
                          <td width="24%" align="left" style="vertical-align: bottom;" ><h2> : '.($date).'</h2></td>
                        </tr>';  
            }else{
                $html .= '<tr>            
                          <td width="12%" align="left" ><h2>WO </h2> </td>  
                          <td width="28%" style="vertical-align: bottom;" ><h2> # '.($workorder_no).'</h2></td>
                          <td width="12%" align="left" ><h2>Quantity </h2> </td>
                          <td width="24%" style="vertical-align: bottom;" ><h2> : '.($total_quantity).'</h2></td>
                          <td width="10%" align="left" ><h2>Date </h2> </td>
                          <td width="24%" align="left" style="vertical-align: bottom;" ><h2> : '.($date).'</h2></td>                          
                        </tr>';  
                
            }           

            if($same_as=="Y"){            
                $html .= '<tr>    
                       <td width="12%" align="left" style="font-weight:bold;">Label Ref. </td>
                       <td width="23%" align="left" >: '.($dataObj->work_order_headers->label_references->name).'</td>       
                       <td width="12%" align="left" style="font-weight:bold;" >Colour </td> 
                       <td width="25%" align="left" >: '.($dataObj->work_order_headers->print_colors).' </td>
                       <td width="20%" align="left" style="font-weight:bold;" >Size Changes After Batching</td> 
                       <td width="7%" align="left" >: '.($dataObj->size_changes_after_batching).' </td>                      
                    </tr>';
            }else{
                 $html .= '<tr>    
                       <td width="12%" align="left" style="font-weight:bold;">Label Ref. </td>
                       <td width="23%" align="left" >: '.($dataObj->work_order_headers->label_references->name).'</td>       
                       <td width="12%" align="left" style="font-weight:bold;" >Colour </td> 
                       <td width="25%" align="left" >: '.($dataObj->work_order_headers->print_colors).' </td>
                       <td width="20%" align="left" style="font-weight:bold;" >Size Changes </td> 
                       <td width="7%" align="left" >: '.($dataObj->size_changes_after_batching).' </td>                      
                    </tr>';
            }

            $html .= '<tr>    
                           <td width="12%" align="left" style="font-weight:bold;">No of Front Clr </td>
                           <td width="23%" align="left" >: '.($dataObj->work_order_headers->no_of_colors_front).'</td>       
                           <td width="12%" align="left" style="font-weight:bold;" >No of Back Clr  </td> 
                           <td width="20%" align="left" >: '.($dataObj->work_order_headers->no_of_colors_back).' </td>  
                           <td width="10%" align="left" style="font-weight:bold;" >Lenght </td> 
                           <td width="10%" align="left" >: '.($dataObj->work_order_headers->lenght).' </td> 
                           <td width="5%" align="left" style="font-weight:bold;" >width </td> 
                           <td width="7%" align="left" >: '.($dataObj->work_order_headers->width).' </td>                      
                        </tr>';

            $html .= '<tr>          
                       <td width="10%" align="left" style="font-weight:bold;">Item Code</td>
                       <td width="40%" align="left" >: '.($dataObj->work_order_headers->label_references->itemLabelReferences[0]->items->name).'</td>
                    </tr>';           
            $html .= '</table>'; 
            if($same_as=="Y"){
                if($same_as_array){  
                    $html .= '<table class="tbl1" width="100%" cellpadding="2">';
                    foreach ($same_as_array as $skey => $sArray) {
                        $html .= '<tr> 
                                   <td width="20%" align="left" > '.($skey).'</td>   
                                   <td width="18%" align="left" >: '.($sArray).'</td>
                                </tr>';
                    }
                    $html .= '</table>'; 
                }
            } 

            $html .= '<br>';
            $html .= '<br>';      
            $html .= '<table class="tbl" width="100%" border="1" cellpadding="2">
                        <tr>
                          <th width="25%" align="left" style="font-weight:bold;"> Size</th> 
                          <th width="15%" align="center" style="font-weight:bold;"> Quantity</th> 
                          <th width="20%" align="center" style="font-weight:bold;"> Theoretical Usage (mtr)</th> 
                          <th width="15%" align="center" style="font-weight:bold;"> Waste (mtr)</th>
                          <th width="15%" align="center" style="font-weight:bold;"> Total Usage (Mtrs)</th> 
                        </tr>';


            $total_quantity=0; $total_theoretical_usage_mtr=0; $total_waste_mtr=0; $total_line_useage_mtr=0;
            foreach($dataObj->mrn_line_rotaries as $rowLns){
                
                $total_quantity+=$rowLns->quantity;
                $total_theoretical_usage_mtr+=$rowLns->theoretical_usage_mtr;
                $total_waste_mtr+=$rowLns->waste_mtr;
                $total_line_useage_mtr+=$rowLns->line_useage_mtr;

                $html .= '<tr>            
                        <td align="left">'.($rowLns->size).'</td>
                        <td align="right">'.($rowLns->quantity).'</td>                      
                        <td align="right">'.($rowLns->theoretical_usage_mtr).'</td>                      
                        <td align="right">'.($rowLns->waste_mtr).'</td> 
                        <td align="right">'.($rowLns->line_useage_mtr).'</td>  
                       </tr>';
            }

            $html .= '<tr>           
                    <td align="left" style="font-weight:bold;">Total</td>
                    <td align="right" style="font-weight:bold;">'.($total_quantity).'</td>
                    <td align="right" style="font-weight:bold;">'.($total_theoretical_usage_mtr).'</td>                  
                    <td align="right" style="font-weight:bold;">'.($total_waste_mtr).'</td> 
                    <td align="right" style="font-weight:bold;">'.($total_line_useage_mtr).'</td>
                  </tr>';

            $waste_percentage=((($total_waste_mtr)/$total_line_useage_mtr)*100);

            $html .= '<tr>           
                    <td colspan="4"align="left" style="font-weight:bold;">Waste Percentage</td>
                    <td align="right" style="font-weight:bold;">'.number_format($waste_percentage, 2).'%</td>                    
                  </tr>';

            $html .= '<tr>           
                    <td width="12%" align="left" style="font-weight:bold;">OEE </td>
                    <td width="10%" align="center" style="font-weight:bold;">'.number_format($dataObj->cal_oee_percentage,2).'%</td>
                    <td width="14%" align="left" style="font-weight:bold;">Performance </td>
                    <td width="10%" align="center" style="font-weight:bold;">'.number_format($dataObj->cal_performance_percentage,2).'%</td> 
                    <td width="12%" align="left" style="font-weight:bold;">Quality </td>                 
                    <td width="10%" align="center" style="font-weight:bold;">'.number_format($dataObj->cal_quality_percentage,2).'%</td> 
                    <td width="12%" align="left" style="font-weight:bold;">Availability </td>
                    <td width="10%" align="center" style="font-weight:bold;">'.number_format($dataObj->cal_availability_percentage,2).'%</td>
                  </tr>'; 
            $html .= '</table>'; 
            $html .= '<br>';
            $html .= '<table class="tbl1" width="100%" cellpadding="2">
                <tr>
                  <th width="50%" align="left" > <h5>To be attached to the WO </h5></th>     
                  <th width="50%" align="right" ></th>
                </tr>';
            $html .= '</table>';
            $html .= '<h4 align="center">---- Print User :'.Auth::user()->name.'-- Print Date & Time : '.Carbon::now().'----</h4>';
            // $html .= '<h4 align="center">---- Print User :'.Auth::user()->name.'-- Print Date & Time : '.Carbon::now().'----</h4>';
            $html .= '<h3>-------------------------------------------------------------------------------------------------------------------------------------------------------</h3>';
            $html .= '<br>';
            $html .= '<br>';

            $html .= '<table class="tbl1" width="100%" cellpadding="2">'; 
            $html .= '<tr>            
                        <td colspan="6" width="100%" align="right" ><h5>Stores copy</h5></td>
                      </tr>'; 

            $html .= '<tr>            
                          <td colspan="2" width="33%" align="left" ><h1>Material Requisition Note</h1> </td>  
                          <td colspan="2" width="40%" style="vertical-align: bottom;" ><h1> # '.($mrn_no).'</h1></td>
                          <td colspan="2" width="27%" align="right" ><h2>Screen ('.($dataObj->table_type).') </h2></td>
                        </tr>';

            if($same_as=="Y"){
                $html .= '<tr>            
                          <td width="12%" align="left" ><h2>Main WO </h2> </td>  
                          <td width="28%" style="vertical-align: bottom;" ><h2> # '.($workorder_no).'</h2></td>
                          <td width="12%" align="left" ><h2>Total Qty </h2> </td>
                          <td width="24%" style="vertical-align: bottom;" ><h2> : '.($total_quantity).'</h2></td>
                          <td width="10%" align="left" ><h2>Date </h2> </td>
                          <td width="24%" align="left" style="vertical-align: bottom;" ><h2> : '.($date).'</h2></td>
                        </tr>';  
            }else{
                $html .= '<tr>            
                          <td width="12%" align="left" ><h2>WO </h2> </td>  
                          <td width="28%" style="vertical-align: bottom;" ><h2> # '.($workorder_no).'</h2></td>
                          <td width="12%" align="left" ><h2>Quantity </h2> </td>
                          <td width="24%" style="vertical-align: bottom;" ><h2> : '.($total_quantity).'</h2></td>
                          <td width="10%" align="left" ><h2>Date </h2> </td>
                          <td width="24%" align="left" style="vertical-align: bottom;" ><h2> : '.($date).'</h2></td>                          
                        </tr>';  
                
            }           

            if($same_as=="Y"){            
                $html .= '<tr>    
                       <td width="12%" align="left" style="font-weight:bold;">Label Ref. </td>
                       <td width="23%" align="left" >: '.($dataObj->work_order_headers->label_references->name).'</td>       
                       <td width="12%" align="left" style="font-weight:bold;" >Colour </td> 
                       <td width="25%" align="left" >: '.($dataObj->work_order_headers->print_colors).' </td>
                       <td width="20%" align="left" style="font-weight:bold;" >Size Changes After Batching</td> 
                       <td width="7%" align="left" >: '.($dataObj->size_changes_after_batching).' </td>                      
                    </tr>';
            }else{
                 $html .= '<tr>    
                       <td width="12%" align="left" style="font-weight:bold;">Label Ref. </td>
                       <td width="23%" align="left" >: '.($dataObj->work_order_headers->label_references->name).'</td>       
                       <td width="12%" align="left" style="font-weight:bold;" >Colour </td> 
                       <td width="25%" align="left" >: '.($dataObj->work_order_headers->print_colors).' </td>
                       <td width="20%" align="left" style="font-weight:bold;" >Size Changes </td> 
                       <td width="7%" align="left" >: '.($dataObj->size_changes_after_batching).' </td>                      
                    </tr>';
            }

            $html .= '<tr>    
                           <td width="12%" align="left" style="font-weight:bold;">No of Front Clr </td>
                           <td width="23%" align="left" >: '.($dataObj->work_order_headers->no_of_colors_front).'</td>       
                           <td width="12%" align="left" style="font-weight:bold;" >No of Back Clr  </td> 
                           <td width="20%" align="left" >: '.($dataObj->work_order_headers->no_of_colors_back).' </td>  
                           <td width="10%" align="left" style="font-weight:bold;" >Lenght </td> 
                           <td width="10%" align="left" >: '.($dataObj->work_order_headers->lenght).' </td> 
                           <td width="5%" align="left" style="font-weight:bold;" >width </td> 
                           <td width="7%" align="left" >: '.($dataObj->work_order_headers->width).' </td>                      
                        </tr>';

            $html .= '<tr>          
                       <td width="10%" align="left" style="font-weight:bold;">Item Code</td>
                       <td width="40%" align="left" >: '.($dataObj->work_order_headers->label_references->itemLabelReferences[0]->items->name).'</td>
                    </tr>';           
            $html .= '</table>'; 
            if($same_as=="Y"){
                if($same_as_array){  
                    $html .= '<table class="tbl1" width="100%" cellpadding="2">';
                    foreach ($same_as_array as $skey => $sArray) {
                        $html .= '<tr> 
                                   <td width="20%" align="left" > '.($skey).'</td>   
                                   <td width="18%" align="left" >: '.($sArray).'</td>
                                </tr>';
                    }
                    $html .= '</table>'; 
                }
            } 

            $html .= '<br>';
            $html .= '<br>';
            $html .= '<table class="tbl1" width="100%">';
            $html .= '<tr> 
                        <td width="15%" align="left" style="font-weight:bold;">Total WO Qty</td>
                        <td width="85%" colspan="5" align="left" style="font-weight:bold;">: '.$total_quantity.'</td>
                       </tr>'; 
            $html .= '<tr> 
                        <td width="15%" align="left" style="font-weight:bold;">Total Meter</td>
                        <td width="85%" colspan="5" align="left" style="font-weight:bold;">: '.($total_line_useage_mtr).'</td>
                       </tr>';

             $html .= '<tr> 
                        <td width="15%" align="left" style="font-weight:bold;">Waste (%)</td>
                        <td width="85%" colspan="5" align="left" style="font-weight:bold;">: '.number_format($waste_percentage, 2).'%</td>
                       </tr>';

            $html .= '<tr>  
                        <td width="15%" align="left" style="font-weight:bold;">Issued Quantity</td>
                        <td width="85%" colspan="5" height="30" align="left" style="font-weight:bold;">:</td>                   
                       </tr>';  
            $html .= '</table>'; 


            $html .= '<br>';          
            $html .= '<br>';  

            $html .= '<table class="tbl1" width="100%">';
            $html .= '<tr>            
                      <td align="center" style="font-weight:bold;">....................................</td>
                      <td align="center" style="font-weight:bold;">....................................</td>
                      <td align="center" style="font-weight:bold;">....................................</td>
                    </tr>';

            $html .= '<tr>            
                      <td align="center" style="font-weight:bold;">Authorized By</td>
                      <td align="center" style="font-weight:bold;">Issued By</td>
                      <td align="center" style="font-weight:bold;">Received By</td>
                    </tr>';
                              
            $html .= '</table>';                
            $html .= '<h4 align="center">---- Print User :'.Auth::user()->name.'-- Print Date & Time : '.Carbon::now().'----</h4>';
          //$html .= '<h1>-------------------------------------------------------------------------------------------------------------</h1>';      
        // $html .= '<br pagebreak="true">';  

            //TPDF::AddPage();
            TPDF::writeHTML($html, true, false, true, false, 'J');
            // TPDF::AddPage();
            // TPDF::setPage(TPDF::getPage());  
            TPDF::Output('Mrn-Screen-'.$workorder_no, 'D');
            TPDF::reset();

        }else{
            return back()->withError('Unable to find data in WO# ' . $dataObj->workorder_no.', Please contact system administrator.');
        } 
    }
}
