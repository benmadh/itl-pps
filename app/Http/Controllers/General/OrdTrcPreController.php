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

class OrdTrcPreController extends Controller
{
    use UtilityHelperGeneral;
    public function __construct(){
        // $this->middleware('permission:order_tracking_designing_access_allow');
    } 

    public function index(){  
        $todate = Carbon::now()->toDateString();          
        $msg=null;
        $msg_emp=null;
        $save_msg=null; 
        $params = [
            'title' => 'Scan Work Orders for Designing Department',          
        ];
        return view('general.ordtrcpre.ordtrcpre')->with($params);       
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
            $departments_id=10;          
            $tracking_des="Designing";  
            $ordertrackings = OrderTracking::create([
                'work_order_header_id' => $wo_id,
                'workorder_no' => $wo_no,
                'date' => $todate,
                'department_id' => $departments_id,
                'tracking_dep'=>$tracking_des,
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
}
