<?php

namespace Larashop\Http\Controllers\Screen;

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

class PartPrintingScreenController extends Controller
{
    use UtilityHelperGeneral;
    public function __construct(){
        $this->middleware('permission:printing_screen_access_allow');
    }  

    public function index(){       
        
        $todate = Carbon::now()->toDateString();
        $shiftList = $this->genShift(null);        
        $msg=null;       
        $save_msg=null;       
        $dep_ids=array('13');         
        $title="Update Print Quantity for Screen (Part)";

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

        return view('screen.partprinting.partprinting')->with($params);
    }

    public function store(Request $request){
        $dep_id=13;
        $dep_ids=array('13');
        $scan_dep_id=13;
        $scanDeptName=$this->getDeptName($scan_dep_id);
        $title="Update Print Quantity for Screen (Part)";
       
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
        }    
                
        $rules = ['from' => 'required|date',
                    'workorder_no' => 'required',
                    'scan_wo' => 'required',
                    'shift' => 'required',                   
                    'employees_id' => 'required',
                    'quantity' =>'required|numeric',
                    'machines_id' => 'required'];                           

        $prd_date=$request['from'];
        $shift=$request['shift'];       
        $employees_id=$request['employees_id'];  
        $machines_id=$request['machines_id']; 
        $quantity = $request['quantity'];
        $size_id = $request['size_id'];       
        $plData=true;         
        $validator = Validator::make($request->all(), $rules);
        $shiftList = $this->genShift(null);
        $constraints = [
            'from' => $prd_date, 
            'shift' => $shift,            
            'employees_id' => $employees_id,
            'machines_id' => $request['machines_id'],
            'workorder_no' => $workorder_no,
            'quantity' => $request['quantity'],
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
            'quantity' => $request['quantity'],              
            'employees' => $employees,           
            'save_msg'=>null,
        ]; 

        if ($validator->fails()) {
           return Response::json(['errors' => $validator->errors()]);
        }
        if ($workorderheaders==null) { 
           return view('screen.partprinting.partprinting')->with($params)->withErrors($validator);
        }
        if ($plData==null) { 
           return view('screen.partprinting.partprinting')->with($params)->withErrors($validator);
        }
        if ($errorDate!=null) {          
           return view('screen.partprinting.partprinting')->with($params)->withErrors($validator);
        } 
        $operaterDetails=$this->getEmpDetails($employees_id);  
        $machineDetails=$this->getMachineDetails($machines_id); 
        $wototQuantity=$this->getWoQty($workorderheaders_id);
        $prdQtyTot=$this->getWoProduceQty($workorderheaders_id); 

        if($prdQtyTot >=$wototQuantity){
            $msg=['workorder_no'=>'Cannot scan !!! This Main Work Order is completed...( '.$workorder_no.' )'];
            return Response::json(['errors' => $msg]);
        }
        
        if($size_id){ 
            $wohLst = WorkOrderHeader::where('id', '=', $workorderheaders_id)
                                      ->where('deleted_at', '=', null)
                                      ->first();
            if($wohLst){
                $wo_id=$wohLst->id;
                $wo_no=$wohLst->workorder_no;
                $woTotQty=$wohLst->wo_quantity;
                $tot_bal_prd_qty=0;

                $dataLine = DB::table('work_order_lines')  
                               ->select('quantity')                       
                               ->where('work_order_header_id', '=' ,$wo_id)
                               ->where('size', '=', $size_id)
                               ->where('deleted_at', '=', null)
                               ->first();
                $bal_prd_qty=0;
                if($dataLine){
                    $woQtySize=$dataLine->quantity;
                    $producedQtySize=$this->getPrintQtySizeWise($wo_id, $size_id);
                   
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
                            'size' => $size_id,
                            'quantity' => $quantity,
                            's_qty'=> $woQtySize,
                            'insert_type' => "Part",
                            'employee_id' => $employees_id,
                            'machine_id' => $machines_id, 
                            'department_id' => $wo_dep_id,                       
                            'company_id' =>Auth::user()->locations->companies->id,
                            'location_id' => Auth::user()->locations->id,                          
                            'created_by'=>Auth::user()->id,
                        ]);

                        if($quantity!=0){
                            $producedQtySize=$this->getPrintQtySizeWise($wo_id, $size_id);                            
                            if($producedQtySize>=$woQtySize){
                                $tracking_des="Printing completed for size (".$size_id."), quantity is : ".$quantity."(". $woQtySize.") Machine : ".$machineDetails->machin_number." Operator : ".$operaterDetails->full_name." ( EPF # ".$operaterDetails->epf_no." )";
                            }else{
                                $tracking_des="Part completed for size (".$size_id."), quantity is : ".$quantity."(". $woQtySize.") Machine : ".$machineDetails->machin_number." Operator : ".$operaterDetails->full_name." ( EPF # ".$operaterDetails->epf_no." )";
                            } 
                           
                            $ordertrackings = OrderTracking::create([
                                'work_order_header_id' => $wo_id,
                                'workorder_no' => $wo_no,
                                'date' => $prd_date, 
                                'department_id' => $wo_dep_id,
                                'tracking_dep'=>"Part - Printing",
                                'tracking_des' => $tracking_des, 
                                'company_id' =>Auth::user()->locations->companies->id,
                                'location_id' => Auth::user()->locations->id,       
                                'created_by'=>Auth::user()->id,           
                            ]); 
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
}
