<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;

Use Larashop\Http\Controllers\General\UtilityHelperGeneral;

use Larashop\Models\General\WorkOrderHeader;
use Larashop\Models\General\WorkOrderLine;
use Larashop\Models\General\OrderTracking;

class OrdTrcPlnController extends Controller
{
    use UtilityHelperGeneral;
    public function __construct(){
        // $this->middleware('permission:order_tracking_plan_access_allow');
    } 

    public function index(){  
        $todate = Carbon::now()->toDateString();          
        $msg=null;
        $msg_emp=null;
        $save_msg=null; 
        $params = [
            'title' => 'Scan Work Orders for Planning Departments',          
        ];
        return view('general.ordtrcpln.ordtrcpln')->with($params);       
    }

    public function store(Request $request){      
       try{ 
          $workorder_no = $request['workorder_no'];           
          $todate = Carbon::now()->toDateString(); 
          $validator = Validator::make($request->all(), [                 
              'workorder_no' => 'required',
          ]);
            
          if ($validator->fails()){
             return Response::json(['errors' => $validator->errors()]);
          }          
          $dataObj = DB::table('work_order_headers')
                                    ->where('workorder_no', '=', $workorder_no)
                                    ->where('deleted_at', '=', null)
                                    ->first();      
                        
          $wo_id=null; 
          $msg=['workorder_no' => array('Work order number not found')];              
          if($dataObj == null){   
            return Response::json(['errors' => $msg]); 
          }else{
            $wo_id=$dataObj->id;
            $wo_no=$dataObj->workorder_no;  
            $departments_id=$dataObj->department_id; 
            $tracking_dep="Planning";
            if($departments_id=="12"){                
                $tracking_des="Planning - PFL Rotary";
            }elseif($departments_id=="13"){
                $tracking_des="Planning - Screen";
            }elseif($departments_id=="14"){
                $tracking_des="Planning - Thermal";
            }elseif($departments_id=="15"){
                $tracking_des="Planning - Offset";
            }elseif($departments_id=="16"){
                $tracking_des="Planning - Digital";    
            }elseif($departments_id=="17"){
                $tracking_des="Planning - Heat Transfer";
            }elseif($departments_id=="18"){
                $tracking_des="Planning - RFID";           
            }else{
                $tracking_dep=$dataObj->departments->name;
                $tracking_des=$dataObj->departments->name;
            }

            $ordertrackings = OrderTracking::create([
                'work_order_header_id' => $wo_id,
                'workorder_no' => $wo_no,
                'date' => $todate,
                'department_id' => $departments_id,
                'tracking_dep'=>$tracking_dep,
                'tracking_des' => $tracking_des, 
                'company_id' =>Auth::user()->locations->companies->id,
                'location_id' => Auth::user()->locations->id, 
                'created_by'=>Auth::user()->id,           
            ]);             
          }            
          return response()->json(['success'=>'Record is successfully added']);

        }catch (Exception $e){ 
          return 'false';
        }
    }

    public function getWoList($workorder_no){
      try{          
           $ordertrackings = DB::table('order_trackings as ord')                                      
                                      ->leftJoin('work_order_headers as wo', 'ord.work_order_header_id', '=', 'wo.id')       
                                      ->leftJoin('departments as woDep', 'wo.department_id', '=', 'woDep.id')      
                                      ->select('ord.*', 'woDep.name as dep_name', 'wo.created_at as wo_create_date', 'wo.deleted_at as wo_deleted_date')       
                                      ->where('ord.workorder_no', '=', $workorder_no)             
                                      ->where('ord.deleted_at', '=', null)
                                      ->orderBy('ord.id', 'desc') 
                                      ->get();

           return response($ordertrackings);
        }catch (Exception $e){
            return 'false';
        }
    }

    public function getScanErrorList($workorder_no){
      
      $dataWo = DB::table('work_order_headers')
                        ->select('id', 'workorder_no','status_id')
                        ->where('workorder_no','=', $workorder_no)
                        ->where('deleted_at', '=', null)
                        ->first();

      $error_msg=array();
      if($dataWo){
        $workorderheaders_id = $dataWo->id; 
        $statuses_id = $dataWo->status_id;        
        $woArrayList=$this->getWoArrayList($workorderheaders_id);
        $wototQuantity=$this->getWoTotalQty($woArrayList);

        if($statuses_id==2){
           array_push($error_msg, 'This WO already completed ( '.$workorder_no.' )');
        }
        if($statuses_id==3){
           array_push($error_msg, 'This WO already cancelled ( '.$workorder_no.' )');
        }          
       
      } 
     
      $data[]=array('error_msg'=>$error_msg);
      return response($data);   

    }  
}
