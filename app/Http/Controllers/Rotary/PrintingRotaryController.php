<?php

namespace Larashop\Http\Controllers\Rotary;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Illuminate\Support\Collection;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;

use Larashop\Models\General\Employee;
use Larashop\Models\General\Machine;
use Larashop\Models\General\WorkOrderHeader;
use Larashop\Models\General\WorkOrderLine;
use Larashop\Models\General\Printing;
use Larashop\Models\General\OrderTracking;

Use Larashop\Http\Controllers\General\UtilityHelperGeneral;

class PrintingRotaryController extends Controller
{
    use UtilityHelperGeneral;
    public function __construct(){
        $this->middleware('permission:printing_rotary_access_allow');
    }  

    public function index(){       
        
        $todate = Carbon::now()->toDateString();
        $shiftList = $this->genShift(null);        
        $msg=null;       
        $save_msg=null;       
        $dep_ids=array('12');         
        $title="Update Print Quantity for PFL Rotary (Complete)";

        $employees = Employee::where('deleted_at', '=', null)
                     ->whereIn('department_id', $dep_ids)
                     ->where('activation', '=', 0)
                     ->orderBy('epf_no', 'asc') 
                     ->get();

        $machines = Machine::where('deleted_at', '=', null)
                     ->where('company_id', '=', Auth::user()->locations->companies->id)
                     ->where('location_id', '=', Auth::user()->locations->id)
                     ->where('machine_category_id','=', 1)
                     ->whereIn('department_id', $dep_ids)                                     
                     ->get();

        $params = [
            'title' => $title,
            'shiftList'=> $shiftList,      
            'from' => $todate,
            'shift' => null,
            'employees_id' => null,
            'machines_id' => null,               
            'workorder_no' => null,
            'workorder_id' => null,
            'quantity' => null,            
            'sup_msg'=>$msg,           
            'save_msg'=>$save_msg,
            'employees' => $employees, 
            'machines' => $machines,
        ];

        return view('rotary.printing.printing')->with($params);
    }

    public function store(Request $request){
        $dep_id=12;
        $dep_ids=array('12');
        $scan_dep_id=12;
        $scanDeptName=$this->getDeptName($scan_dep_id);
        $title="Update Print Quantity for PFL Rotary (Complete)";
        
        $workorder_no = $request['workorder_no'];
        $scan_wo = $request['scan_wo'];

        if($workorder_no!=$scan_wo ){
          $msg=['workorder_no' => array('Dose not match with scan Wo # '.$workorder_no)];            
            return Response::json(['errors' => $msg]);
        }

        $workorderheaders = DB::table('work_order_headers')
                                ->where('workorder_no', '=', $workorder_no)
                                ->where('deleted_at', '=', null)
                                ->first(); 

        $msg=null;
        $errorDate=null;                 
        if($workorderheaders == null){
          $msg=['workorder_no'=>'Work order number is not found in database...( '.$workorder_no.' )'];
          return Response::json(['errors' => $msg]);
        }else{  
          $workorderheaders_id=$workorderheaders->id;          
          $workorderno=$workorderheaders->workorder_no;
          $main_workorder_no=$workorderheaders->main_workorder_no;
          $main_workorder_id=$workorderheaders->main_workorder_id;
          $wo_dep_id=$workorderheaders->department_id;
          $deptName=$this->getDeptName($wo_dep_id);
          if($wo_dep_id!=$dep_id){
              $msg=['workorder_no' => array('Cannot Scan, This WO # ('.$workorderno.' ) should be scan in '.$deptName.' department!')];
              return Response::json(['errors' => $msg]);
          }

          if($scan_wo!=$main_workorder_no){
              $msg=['workorder_no' => array('Invalid Main WO # ('.$workorderno.' ) You should be scan main WO # is '.$main_workorder_no)];
              return Response::json(['errors' => $msg]);
          }
        }    
                
        $rules = ['from' => 'required|date',
                    'workorder_no' => 'required',
                    'scan_wo' => 'required',
                    'shift' => 'required',                   
                    'employees_id' => 'required',
                    'machines_id' => 'required'];                           

        $prd_date=$request['from'];
        $shift=$request['shift'];       
        $employees_id=$request['employees_id'];  
        $machines_id=$request['machines_id']; 
        $size_id_array = $request->size_id;
        $wo_ids_array = $request->wo_ids; 
        $wo_array = array();
        if($wo_ids_array){
            foreach($wo_ids_array as $rowWoIds){
                $strLen=strlen($rowWoIds);
                $l_wo_no=trim(substr($rowWoIds, 0, -6));
                array_push($wo_array, $l_wo_no);
            }
        }
        $plData=true;         
        $validator = Validator::make($request->all(), $rules);
        $shiftList = $this->genShift(null);

        $constraints = [
            'from' => $prd_date, 
            'shift' => $shift,            
            'employees_id' => $employees_id,
            'machines_id' => $request['machines_id'],
            'workorder_no' => $workorder_no,
        ];

        $employees = Employee::where('deleted_at', '=', null)
                                 ->whereIn('department_id', $dep_ids)
                                 ->get();

        $params = [
            'title' => $title,
            'shiftList' => $shiftList,
            'sup_msg'=>$msg,
            'from' => $request['from'], 
            'shift' => $request['shift'],            
            'employees_id' => $request['employees_id'],
            'machines_id' => $request['machines_id'],
            'workorder_no' => $workorder_no,            
            'employees' => $employees,           
            'save_msg'=>null,
        ]; 

        if ($validator->fails()) {
           return Response::json(['errors' => $validator->errors()]);
        }
        if ($workorderheaders==null) { 
           return view('rotary.printing.printing')->with($params)->withErrors($validator);
        }
        if ($plData==null) { 
           return view('rotary.printing.printing')->with($params)->withErrors($validator);
        }
        if ($errorDate!=null) {          
           return view('rotary.printing.printing')->with($params)->withErrors($validator);
        } 
        $operaterDetails=$this->getEmpDetails($employees_id);  
        $machineDetails=$this->getMachineDetails($machines_id);        
        $woArrayList=$this->getWoArrayList($main_workorder_id);
        $wototQuantity=$this->getWoTotalQty($woArrayList);         
        $prdQtyTot=$this->getProduceQty($woArrayList);
        if($prdQtyTot >=$wototQuantity){
            $msg=['workorder_no'=>'Cannot scan !!! All WOs are completed...( '.$workorder_no.' )'];
            return Response::json(['errors' => $msg]);
        }
        
        if(!$size_id_array){  
            if(!$wo_array){                   
                $wohLst = WorkOrderHeader::select('id','workorder_no','wo_quantity')
                                            ->where('main_workorder_id', '=', $workorderheaders_id)
                                            ->where('deleted_at', '=', null)
                                            ->get();
            }else{                    
                $wohLst = WorkOrderHeader::select('id','workorder_no','wo_quantity')
                                      ->where('deleted_at', '=', null)
                                      ->whereIn('workorder_no', $wo_array)
                                      ->get();

            }
             
            foreach($wohLst as $rowHead){
                $wo_id=$rowHead->id;
                $wo_no=$rowHead->workorder_no;
                $woTotQty=$rowHead->wo_quantity;
                $dataLine = DB::table('work_order_lines')                         
                              ->where('deleted_at', '=', null)
                              ->where('work_order_header_id', '=' ,$wo_id)
                              ->get();

                $tot_bal_prd_qty=0;
                foreach($dataLine  as $dataRowLn) {
                    $l_size_no=$dataRowLn->size;
                    $l_size_qty=$dataRowLn->quantity;               
                    $woQtySize=$this->getWoQtySizeWise($wo_id, $l_size_no);
                    $producedQtySize=$this->getPrintQtySizeWise($wo_id, $l_size_no);
                   
                    $bal_prd_qty=0;
                    $tracking_des=null;
                    if($producedQtySize>=$woQtySize){
                      $bal_prd_qty=0;
                    }else{
                        $bal_prd_qty=$woQtySize-$producedQtySize;
                        $dataObj = Printing::create([
                            'work_order_header_id' => $wo_id,
                            'workorder_no' => $wo_no,
                            'date' => $prd_date,
                            'shift' => $shift,
                            'size' => $l_size_no,
                            'quantity' => $bal_prd_qty,
                            's_qty'=> $l_size_qty,
                            'insert_type' => "Completed",
                            'employee_id' => $employees_id,
                            'machine_id' => $machines_id, 
                            'department_id' => $wo_dep_id,                       
                            'company_id' =>Auth::user()->locations->companies->id,
                            'location_id' => Auth::user()->locations->id,                          
                            'created_by'=>Auth::user()->id,
                        ]);                  
                    }
                    $tot_bal_prd_qty+=$bal_prd_qty;
                }
                if($tot_bal_prd_qty!=0){
                    $tracking_des="Printing completed, quantity is : ".$tot_bal_prd_qty."(". $woTotQty.") Machine : ".$machineDetails->machin_number." Operator : ".$operaterDetails->full_name." ( EPF # ".$operaterDetails->epf_no." )"; 
                    
                    $ordertrackings = OrderTracking::create([
                        'work_order_header_id' => $wo_id,
                        'workorder_no' => $wo_no,
                        'date' => $prd_date, 
                        'department_id' => $wo_dep_id,
                        'tracking_dep'=>"Printing Completed",
                        'tracking_des' => $tracking_des, 
                        'company_id' =>Auth::user()->locations->companies->id,
                        'location_id' => Auth::user()->locations->id,       
                        'created_by'=>Auth::user()->id,           
                    ]); 
                }
                $wo_qty=$this->getWoQtyGen($wo_id);
                $finalQty=$this->getPrintQtyGen($wo_id);
                if($finalQty>=$wo_qty){
                    $input03['print_status_id'] = 2;
                    $this->updateRecords('work_order_headers',array($wo_id),$input03);
                }

            }            
        }else{ 
            if(!$wo_array){                   
                $wohLst = WorkOrderHeader::select('id','workorder_no','wo_quantity')
                                            ->where('main_workorder_id', '=', $workorderheaders_id)
                                            ->where('deleted_at', '=', null)
                                            ->get();
            }else{                    
                $wohLst = WorkOrderHeader::select('id','workorder_no','wo_quantity')
                                      ->where('deleted_at', '=', null)
                                      ->whereIn('workorder_no', $wo_array)
                                      ->get();

            }

            foreach($wohLst as $rowHead){
                $wo_id=$rowHead->id;
                $wo_no=$rowHead->workorder_no;
                $woTotQty=$rowHead->wo_quantity;
                $tot_bal_prd_qty=0;
                foreach($size_id_array as $rowSize){
                    $strLen=strlen($rowSize);
                    $l_size_no=trim(substr($rowSize, 0, -6));
                    
                    $dataLine = DB::table('work_order_lines')  
                               ->select('quantity')                       
                               ->where('work_order_header_id', '=' ,$wo_id)
                               ->where('size', '=', $l_size_no)
                               ->where('deleted_at', '=', null)
                               ->first();
                    $bal_prd_qty=0;
                    if($dataLine){
                        $woQtySize=$dataLine->quantity;
                        $producedQtySize=$this->getPrintQtySizeWise($wo_id, $l_size_no);
                       
                        $bal_prd_qty=0;
                        $tracking_des=null;
                        if($producedQtySize>=$woQtySize){
                          $bal_prd_qty=0;
                        }else{
                            $bal_prd_qty=$woQtySize-$producedQtySize;
                            $dataObj = Printing::create([
                                'work_order_header_id' => $wo_id,
                                'workorder_no' => $wo_no,
                                'date' => $prd_date,
                                'shift' => $shift,
                                'size' => $l_size_no,
                                'quantity' => $bal_prd_qty,
                                's_qty'=> $woQtySize,
                                'insert_type' => "Completed",
                                'employee_id' => $employees_id,
                                'machine_id' => $machines_id, 
                                'department_id' => $wo_dep_id,                       
                                'company_id' =>Auth::user()->locations->companies->id,
                                'location_id' => Auth::user()->locations->id,                          
                                'created_by'=>Auth::user()->id,
                            ]);

                            if($bal_prd_qty!=0){
                                $tracking_des="Printing completed for size (".$l_size_no."), quantity is : ".$bal_prd_qty."(". $woQtySize.") Machine : ".$machineDetails->machin_number." Operator : ".$operaterDetails->full_name." ( EPF # ".$operaterDetails->epf_no." )"; 
                                
                                $ordertrackings = OrderTracking::create([
                                    'work_order_header_id' => $wo_id,
                                    'workorder_no' => $wo_no,
                                    'date' => $prd_date, 
                                    'department_id' => $wo_dep_id,
                                    'tracking_dep'=>"Printing Completed",
                                    'tracking_des' => $tracking_des, 
                                    'company_id' =>Auth::user()->locations->companies->id,
                                    'location_id' => Auth::user()->locations->id,       
                                    'created_by'=>Auth::user()->id,           
                                ]); 
                            }
                        }
                    }

                }

                $wo_qty=$this->getWoQtyGen($wo_id);
                $finalQty=$this->getPrintQtyGen($wo_id);
                if($finalQty>=$wo_qty){
                    $input03['print_status_id'] = 2;
                    $this->updateRecords('work_order_headers',array($wo_id),$input03);
                }
            }            
        }

        return response()->json(['success'=>'Record is successfully added ( '.$workorderno.' )']);  
        
    }

   
    public function getSizeBrackDown($scan_wo){ 
     
        $dataMst = WorkOrderHeader::where('deleted_at', '=', null)
                                    ->where('workorder_no', '=', $scan_wo) 
                                    ->orderBy('id', 'DESC')     
                                    ->first(); 

        $size_array=[];
        $size_array_list = array();
        if($dataMst){         
            $main_id=$dataMst->main_workorder_id;
            $main_wo=$dataMst->main_workorder_no;
            
            $wohLst = WorkOrderHeader::where('deleted_at', '=', null)
                                      ->where('main_workorder_id', '=', $main_id) 
                                      ->get();
            $k=0;
            $size_array_list = array();
            foreach($wohLst  as $wohRow) {
              $header_id=$wohRow->id;
              $wlLst = WorkOrderLine::where('deleted_at', '=', null)                       
                                    ->where('work_order_header_id',$header_id)
                                    ->get();
                       
              foreach($wlLst  as $wlRow) {
                $size_no=$wlRow->size;

                if (!array_key_exists($size_no, $size_array)){                                            
                    $size_array[$size_no]=0;                               
                    array_push($size_array_list, $size_no);
                }            
                $k++;
              }          
            }
          }else{
             $size_array=[];
          }                          
        
          return response($size_array_list);
    }

    public function getWoLst($scan_wo){ 
     
        $dataMst = WorkOrderHeader::where('deleted_at', '=', null)
                                    ->where('workorder_no', '=', $scan_wo) 
                                    ->orderBy('id', 'DESC')     
                                    ->first(); 

        $wo_array=[];
        $wo_array_list = array();
        if($dataMst){         
            $main_id=$dataMst->main_workorder_id;
            $main_wo=$dataMst->main_workorder_no;
            
            $wohLst = WorkOrderHeader::where('deleted_at', '=', null)
                                      ->where('main_workorder_id', '=', $main_id) 
                                      ->get();
            $k=0;
            $wo_array_list = array();
            foreach($wohLst  as $wohRow) {
                $header_id=$wohRow->id;
                $size_no=$wohRow->workorder_no;
                if (!array_key_exists($size_no, $wo_array)){                                            
                    $wo_array[$size_no]=0;                               
                    array_push($wo_array_list, $size_no);
                } 
                $k++;                        
            }
          }else{
             $wo_array=[];
          }                          
        
          return response($wo_array_list);
    }
    public function getPrintingDetailsRotary(){ 
     
      $dep_ids=array('12');      
      $mstDataLst = Printing::where('deleted_at', '=', null)
                            ->whereIn('department_id', $dep_ids)
                            ->orderBy('id', 'DESC')
                            ->limit(20)
                            ->get();

      $array_list =[];
      $x=0;
      $totQuantity=0;
      
      foreach($mstDataLst  as $row) {
        $wo_id=$row->work_order_header_id;
        $workorderno=$row->workorder_no;
        $size_no=$row->size;
        $woQtySize=$this->getWoQtySizeWise($wo_id, $size_no);
        $producedQtySize=$this->getPrintQtySizeWise($wo_id, $size_no);

        $date=$row->date;
        $shift=$row->shift; 
        $quantity=$row->quantity;
        $totQuantity+=$quantity;
        $cumulative_qty=$producedQtySize;
        
        $balance_qty=$cumulative_qty-$woQtySize;
        $array_list[$x]["id"]=$row->id;
        $array_list[$x]["date"]=$date;
        $array_list[$x]["shift"]=$shift;
        $array_list[$x]["workorder_no"]=$workorderno;            
        $array_list[$x]["prd_qty"]=$quantity;        
        $array_list[$x]["size_no"]=$size_no;
        $array_list[$x]["size_qty"]=$woQtySize;
        $array_list[$x]["cumulative_qty"]=$cumulative_qty;
        $array_list[$x]["balance_qty"]=$balance_qty;          
        $array_list[$x]["epf_des"]=$row->employees->epf_no.' - '.$row->employees->call_name;

        $x++;
      }      
      return Response::json($array_list);
    }  
}
