<?php

namespace Larashop\Http\Controllers\Rotary;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Larashop\Models\General\LabelReference;
use Larashop\Models\General\WorkOrderHeader;
use Larashop\Models\General\WorkOrderLine;
use Larashop\Models\General\Department;
use Larashop\Models\General\Machine;
use Larashop\Models\General\Holiday;
use Larashop\Models\General\PlnBoard;

use Illuminate\Database\Eloquent\ModelNotFoundException;
Use Larashop\Http\Controllers\General\UtilityHelperGeneral;

class PlnBoardRotaryController extends Controller
{
    use UtilityHelperGeneral;
    public function __construct(){
        $this->middleware('permission:planning_board_rotary_access_allow');  

    }

    public function index(){
    	$dep_id=12;	
    	$baseDataLst = $this->getBaseData($dep_id);
	    $from_date=$baseDataLst->date; 
        $to_date = $this->getMaxPlanDate($dep_id);
       
		$end = Carbon::parse($to_date);
		$now = Carbon::parse($from_date);
		$length = $end->diffInDays($now)+1;
		$grid_length = $length;
 		if($grid_length==0){
 			$length=1;
 		} 
		$body_details_arr = [];
		$dates_color = [];

		$machinesHeader = [];
		$dates_array = [];
		$x=0;
		$k=1;

		for ($i=0; $i<$length; $i++) {
		    
		    $dat_test = date('Y-m-d', strtotime(Carbon::parse($from_date)->addDays($i)));		    
		    $short_date=strtoupper(date('D', strtotime($dat_test)));
		    $pl_date = date('Y-m-d', strtotime(Carbon::parse($from_date)->addDays($i)));

		    $holidays = DB::table('holidays')
                               ->leftJoin('day_types', 'holidays.day_types_id', '=', 'day_types.id')
                               ->select('holidays.*', 'day_types.name as daytype_name', 'day_types.colorpicker_id as colorpicker_id')
                               ->where('holidays.date', '=', $dat_test )
                               ->where('holidays.companies_id','=', Auth::user()->locations->companies->id) 
                               ->where('holidays.deleted_at', '=', null)
                               ->first();

		    if($holidays){
		        $daytype_name = $holidays->daytype_name;
		        $colorpicker_id = $holidays->colorpicker_id;           
		    }else{
		        if($short_date=="SAT"){
		           $colorpicker_id = "#c5f5f0"; 
		           $daytype_name="SAT";             
		        }elseif($short_date=="SUN"){
		               $colorpicker_id = "#f8d2d2"; 
		               $daytype_name="SUN";                 
		        }else{
		           $colorpicker_id = "#d9dbdf"; 
		           $daytype_name="NOR";              
		        }		        
		    }
		    
		    for($j=0; $j<2;$j++){	        	
				$dates_array[$x]["pl_date"]=$pl_date;
				$dates_array[$x]["short_date"]=$short_date;
				$dates_array[$x]["day_typ"]=$daytype_name;
				$dates_array[$x]["day_type_clr"]=$colorpicker_id;
				$e_shift="";
				if($j==0){
					$dates_array[$x]["shift"]="D";
					$e_shift="D";
				}elseif($j==1){
					$dates_array[$x]["shift"]="N";
					$e_shift="N";
				}else{
					dd($j);
				}
				$machines = Machine::where('deleted_at', '=', null)
			                      	->where('department_id','=', $dep_id) 
			                       	->where('company_id','=', Auth::user()->locations->companies->id) 
			                    	->where('location_id','=', Auth::user()->locations->id)  
			                      	->whereIn('machine_category_id',array('1'))
			                      	->orderBy('machin_number')                           
			                      	->get();

				foreach($machines as $value) {
					$plan_details = PlnBoard::where('deleted_at', '=', null)
					                      	->where('pln_date','=', $pl_date)
					                      	->where('pln_shift','=', $e_shift)   
					                      	->where('machine_id','=', $value->id)             
					                      	->get(); 
                    $psd_pre=0;
                    $p_mnt_total=0;	
                    $p_qty_total=0;	           	           
		            $p_lt_mnt_total=0;
		            $p_sc_mnt_total=0;
		            $p_cc_mnt_total=0;
		            $p_size_change_total=0; 
                    foreach($plan_details as $plan_value) {
                    	$workorderheaders_id = $plan_value->work_order_header_id;
                    	$labelreferences_id = $plan_value->work_order_headers->label_references->name;
                    	$plan_quantity = $plan_value->pln_qty;
                    	$colorpicker_id = $plan_value->work_order_headers->order_types->color_picker_id;
                    	$plan_type = $plan_value->plan_type;
                    	if($plan_type=="D"){
                    		$colorpicker_id="#d0dc17"; //privuas WOS
                    	}
                    	if($plan_type=="AL"){
                    		$colorpicker_id="#FF0000"; //deleivery date RED
                    	}
                    	if($plan_type=="APD"){
                    		$colorpicker_id="#00FFFF";
                    	}
                    	$deliverydate = $plan_value->work_order_headers->mwo_delivery_date;
                    	$wo_quantity = $plan_value->wo_tot_qty;
                    	$customers_name = $plan_value->work_order_headers->customers->name;
                    	$cureent_location= $this->getCurrentLocation($workorderheaders_id);
                    	
                    	$dates_array[$x]["machine_id"][$value->id][$plan_value->id]=substr($customers_name,0,4).' / '.$labelreferences_id.'-'.$plan_quantity.'('.$wo_quantity.') '.$deliverydate.'-'.$cureent_location;

                    	$dates_array[$x]["wo_colour_id"][$value->id][$plan_value->id]=$colorpicker_id;                   	

                    	$arr_woplst=[];
		                $wol_string="";
		                $main_wo_id=$plan_value->work_order_header_id;
		                $woplst = WorkOrderHeader::where('main_workorder_id', '=', $plan_value->work_order_header_id)->get();
		                $odr_typ="";
		                foreach($woplst  as $wo_row) {	
		                   $row_id=$wo_row->id;
		                   if($row_id==$main_wo_id){
		                   	 $odr_typ="Main";
		                   }else{
		                   	 $odr_typ="";
		                   }
		                   $pcudate= date('m-d', strtotime(Carbon::parse($wo_row->mwo_delivery_date))); 
		                   $print_clr=$wo_row->print_colors; 
		                   if($print_clr="Default"){
		                   	$print_clr="";
		                   }                 
		                   $arr_woplst[]=$wo_row->workorder_no.' ( '.$wo_row->wo_quantity.' ) '.$print_clr.' ('.$pcudate.')'.$odr_typ;	
		                } 
		                $dates_array[$x]["wo_list_arr"][$value->id][$plan_value->id]=$arr_woplst;

		                $p_qty_total+=$plan_value->pln_qty;
						$p_mnt_total+=$plan_value->pln_mnt;
						$p_size_change_total+=$plan_value->work_order_headers->size_changes;	                

                    }
                    
                    if($e_shift=="D"){
	                    $psd_pre= (int)((($p_mnt_total)/450)*100);	                  
	                    $dates_array[$x]["machine_id"][$value->id]['psd_pre'.$k] = "Capacity-".$psd_pre.' % (MQS:'.$p_mnt_total.'|'.$p_qty_total.'|'.$p_size_change_total.')';	                   
                    }
                    if($e_shift=="N"){
	                    $psn_pre= (int)((($p_mnt_total)/450)*100);	                   
	                    $dates_array[$x]["machine_id"][$value->id]['psn_pre'.$k] = "Capacity-".$psn_pre.' % (MQS:'.$p_mnt_total.'|'.$p_qty_total.'|'.$p_size_change_total.')';	                    
                    }
                    $k++;
				}

		   		$x++; 	
		  	}	       
		}
	 	
	 	$machines_details = Machine::where('deleted_at', '=', null)
			                      	->where('department_id','=', $dep_id) 
			                       	->where('company_id','=', Auth::user()->locations->companies->id) 
			                    	->where('location_id','=', Auth::user()->locations->id)  
			                      	->whereIn('machine_category_id', array('1'))
			                      	->orderBy('machin_number')                           
			                      	->get();

		foreach($machines_details as $row) {
			$machinesHeader[$row->id] = $row->machin_number; 
        }       
        $searchingVals = null;	 
        //dd($dates_array);
        $params = [
            'title' => 'Planning Board for Rotary Department',	            
            'machinesHeader' => $machinesHeader,
            'dates_array' => $dates_array,  
        ];
     
        return view('rotary.plnboard.plnboard')->with($params);
	}

	public function change_wo_date(Request $request, $dropDate,$dropShift,$dropMachine,$planningboard_id){       
        	
		try{

			$machines = DB::table('machines')
	                    ->where('machin_number', '=', $dropMachine)
	                    ->first();

			$previousPlanningDetails = DB::table('pln_boards')
	                    ->where('id', '=', $planningboard_id)
	                    ->first();	
	        $plan_type="M";
	        $previous_plan_date=$previousPlanningDetails->pln_date;
	        $previous_plan_shift=$previousPlanningDetails->pln_shift;
	        $previous_machine_id=$previousPlanningDetails->machine_id;

			$planningboards = PlnBoard::findOrFail($planningboard_id);
			$planningboards->pln_date = $dropDate;           
			$planningboards->pln_shift = $dropShift; 
			$planningboards->machine_id = $machines->id; 
			$planningboards->plan_type = $plan_type;
			$planningboards->updated_by = Auth::user()->id;  
			$planningboards->updated_at = date('Y-m-d H:i:s');			          
			$planningboards->save();


			$arr_details = [];
			$drag_percentage=0;			
            $p_mnt_drag=0;
            $p_qty_drag=0;		           	           
            $p_lt_mnt_drag=0;
            $p_sc_mnt_drag=0;
            $p_cc_mnt_drag=0;            
            $p_size_change_drag=0;
            $dragDivDetails = PlnBoard::where('deleted_at', '=', null)					 
                      ->where('pln_date','=', $previous_plan_date)
                      ->where('pln_shift','=', $previous_plan_shift)   
                      ->where('machine_id','=', $previous_machine_id)             
                      ->get();           
            $x=0;
            foreach($dragDivDetails as $dragPr) { 
            	$p_mnt_drag+=$dragPr->pln_mnt;
            	$p_qty_drag+=$dragPr->pln_qty;				
				$p_size_change_drag+=$dragPr->work_order_headers->size_changes;
            }

            $drag_percentage= (int)((($p_mnt_drag)/450)*100);           
            $arr_details[$x]["drag_pr"]="Capacity at ".$drag_percentage.' % (MQS :'.$p_mnt_drag.'|'.$p_qty_drag.'|'.$p_size_change_drag.')';
            
			$dropDivDetails = PlnBoard::where('deleted_at', '=', null)					 
                      ->where('pln_date','=', $dropDate)
                      ->where('pln_shift','=', $dropShift)   
                      ->where('machine_id','=', $machines->id)             
                      ->get(); 

            $drop_percentage=0;
            $p_mnt_drop=0;	
            $p_qty_drop=0;	           	           
            $p_lt_mnt_drop=0;
            $p_sc_mnt_drop=0;
            $p_cc_mnt_drop=0; 
            $p_size_change_drop=0;
            $x=1;
            foreach($dropDivDetails as $dropPr) {
            	$p_mnt_drop+=$dropPr->pln_mnt;
            	$p_qty_drop+=$dropPr->pln_qty;				
				$p_size_change_drop+=$dropPr->work_order_headers->size_changes;
            }

            $drop_percentage= (int)((($p_mnt_drop)/450)*100);

            $arr_details[$x]["drop_pr"]="Capacity at ".$drop_percentage.' % (MQS :'.$p_mnt_drop.'|'.$p_qty_drop.'|'.$p_size_change_drop.')';         
            return Response::json($arr_details); 
			//return $drop_percentage; 
		}catch (Exception $e){   
          return $e;  
        }  

    }
    public function process(Request $request){

    }

    public function show($id) {
   	
    }

    public function getWoPlanListRotary($workorderno){ 
      try{
          $workorderheaders = DB::table('work_order_headers')              
            ->where('workorder_no', 'like', '%'.$workorderno.'%')
            ->where('deleted_at','=', null)
            ->first();

          $workorderheaders_id =$workorderheaders->id;

          if(!empty($workorderheaders_id)){
            $planningboards = DB::table('pln_boards')
              ->leftJoin('machines', 'pln_boards.machine_id', '=', 'machines.id') 
              ->leftJoin('work_order_headers', 'pln_boards.work_order_header_id', '=', 'work_order_headers.id')                    
              ->select('pln_boards.*', 'machines.machin_number as machin_number','work_order_headers.mwo_delivery_date as deliverydate')       
              ->where('pln_boards.work_order_header_id', '=', $workorderheaders_id) 
              ->where('pln_boards.deleted_at','=', null)                 
              ->orderBy('pln_boards.pln_date')
              ->get();
          }else{
            return 'dimuth';
          }          
          return response($planningboards);
        }catch (Exception $e){
            return 'dimuth3';
        }
    }   
}