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

class OrderTrackingController extends Controller
{
    use UtilityHelperGeneral;
    public function index(){         
        
        $workorders = null;
        $searchingVals = null;
        $woHeaderDataList = null;
        $dates_array = null;
        $orderTrcList = null;
        $plancList=null;
        $productionList=null;
        $mrnLst=null;
        $operatorList = $this->genOperator(null);
        $operator="%";
        $params = [
            'title' => 'Order Tracking Details',            
            'searchingVals' => $searchingVals,  
            'workorders' => $workorders,
            'woHeaderDataList' => $woHeaderDataList,
            'dates_array' => $dates_array,
            'orderTrcList' => $orderTrcList,
            'productionList' => $productionList,
            'plancList' => $plancList, 
            'mrnLst' => $mrnLst, 
            'operatorList' => $operatorList,
            'operator' => $operator,            
        ];

        return view('general.OrderTracking.OrderTracking')->with($params);
    }

    public function search(Request $request){

        $constraints = [
            'wonumber' => $request['wonumber'],  
        ];

        $workorderno=$request['wonumber'];
        $operator=$request['operator'];

        if($operator=="%"){
            $woHeaderDataList = DB::table('work_order_headers')
                        ->leftJoin('order_types', 'work_order_headers.order_type_id', '=', 'order_types.id')
                        ->leftJoin('label_references', 'work_order_headers.label_reference_id', '=', 'label_references.id')
                        ->leftJoin('departments', 'work_order_headers.department_id', '=', 'departments.id')
                        ->leftJoin('customers', 'work_order_headers.customer_id', '=', 'customers.id')
                        ->select('work_order_headers.*', 'order_types.name as orderstype_name','label_references.name as references_name', 'departments.name as departments_name',  'customers.name as cus_name' ) 
                        ->where('work_order_headers.workorder_no', 'like', '%'.trim($workorderno).'%')
                        ->orderBy('work_order_headers.id', 'asc')                     
                        ->get();       

         
            $getWoId = DB::table('work_order_headers')
                        ->where('workorder_no', 'like', '%'.trim($workorderno).'%')
                        ->orderBy('id', 'desc')
                        ->first();

        }else{

            $woHeaderDataList = DB::table('work_order_headers')
                        ->leftJoin('order_types', 'work_order_headers.order_type_id', '=', 'order_types.id')
                        ->leftJoin('label_references', 'work_order_headers.label_reference_id', '=', 'label_references.id')
                        ->leftJoin('departments', 'work_order_headers.department_id', '=', 'departments.id')
                        ->leftJoin('customers', 'work_order_headers.customer_id', '=', 'customers.id')
                        ->select('work_order_headers.*', 'order_types.name as orderstype_name','label_references.name as references_name', 'departments.name as departments_name',  'customers.name as cus_name' ) 
                        ->where('work_order_headers.workorder_no', '=', trim($workorderno))
                        ->orderBy('work_order_headers.id', 'asc')                     
                        ->get();   

            $getWoId = DB::table('work_order_headers')
                        ->where('workorder_no', '=', trim($workorderno))
                        ->orderBy('id', 'desc')
                        ->first();


        }
        $data=array(); 
        $dates_array = [];
        $dates_array[0]["fldsRcStatus"]="YES";
        $dates_array[0]["fldsFlg"]="Y";     
        $mrnLst=null;
        $statuses_id=1;  
        if($getWoId){
            $main_workorder_id=$getWoId->main_workorder_id;
            $workorder_id=$getWoId->id;
            $e_workorder=$getWoId->workorder_no;
            $main_workorder=$getWoId->main_workorder_no;
            $woquantity=$getWoId->wo_quantity;
            $statuses_id=$getWoId->status_id;
            $department_id=$getWoId->department_id;

            $dates_array[0]["statuses_id"]=$statuses_id;
            $mainWoArrayLst= $this->getMainWoIdArrayLst($main_workorder_id, $main_workorder);
            //dd($mainWoArrayLst);
            $dataHeader = DB::table('work_order_lines')
                            ->where('work_order_header_id','=',$main_workorder_id)
                            ->get();

            $header_size= array();
            $size_qty_arr=[];
            $k=0;
            foreach($dataHeader  as $headRow) {
                $header_size[]=array('size_head'=>$headRow->size);
                $size_qty_arr[$k]=0;
                $k++;
            }
            $size_qty_arr[$k+1]=0;           
            $wohLst = DB::table('work_order_headers')
                    ->where('main_workorder_id', '=', $main_workorder_id)
                    ->get();

            $wohlst_array_list = array();
            
            $wo_tot=0; 
            foreach($wohLst  as $wohRow) {
                $header_id=$wohRow->id;
                $workorderno=$wohRow->workorder_no;
                $deliverydate=$wohRow->delivery_date;
                $wo_quantity=$wohRow->wo_quantity;
                $wo_quantity_tot=0; 
                $size_qty_n= array();
                $j=0;
                foreach($dataHeader  as $sizeRow) {
                    $sizeno=$sizeRow->size;

                    $wolLst = DB::table('work_order_lines')
                            ->where('work_order_header_id','=',$header_id)
                            ->where('size',$sizeno)
                            ->first();

                    $size_qty=0;
                    if($wolLst){
                       $size_qty=$wolLst->quantity;
                    }
                    $size_qty_arr[$j]+=$size_qty;
                    $size_qty_n[]=array('size_qty'=>$size_qty);
                    $wo_quantity_tot+=$size_qty;               
                    $j++;
                }
                $size_qty_arr[$j+1]+=$wo_quantity_tot;
                $wo_tot+=$wo_quantity;
                $wohlst_array_list[]=array('workorderno'=>$workorderno,
                              'deliverydate'=>$deliverydate,
                              'size_qty'=>$size_qty_n,
                              'size_tot_qty'=>$wo_quantity_tot,                         
                              'wo_quantity'=>$wo_quantity);

            } 

            
            $dates_array[0]["wo_quantity_tot"]=$wo_tot;
            $dates_array[0]["woquantity"]=$woquantity;            
            $dates_array[0]["woh_des"]=$wohlst_array_list;
            $dates_array[0]["header_des"]=$header_size;
            $dates_array[0]["size_qty_arr"]=$size_qty_arr;
            $dates_array[0]["main_workorder"]=$main_workorder;
            $dates_array[0]["department_id"]=$department_id;
            $data[]=array('wo_quantity_tot'=>$wo_tot,
                          'woh_des'=>$wohlst_array_list, 
                          'header_des'=>$header_size,  
                          'size_qty_arr'=>$size_qty_arr); 

            $orderTrcList = OrderTracking::where('deleted_at', '=', null)                        
                                ->where('workorder_no','=',$e_workorder)
                                ->get();

            $orderLstSts = OrderTracking::where('deleted_at', '=', null)                        
                                ->where('workorder_no','=',$e_workorder)
                                ->orderBy('id', 'desc')
                                ->first();
                       
            $lastStatus="Not Available Tracking Details";
            $lastStatusDate="";
            if($orderLstSts){
                $lastStatus=$orderLstSts->tracking_dep;
                $lastStatusDate=$orderLstSts->created_at;
                if($lastStatus==""){
                    $lastStatus=$orderLstSts->tracking_des;
                }
            }
            //dd($orderLstSts);            
            $dates_array[0]["last_status"]=$lastStatus;
            $dates_array[0]["last_status_date"]=$lastStatusDate;            

        }else{           
            $error_msg="WO Not Found…!";
            $dates_array[0]["last_status"]=null;
            $dates_array[0]["last_status_date"]=null;
            $dates_array[0]["statuses_id"]=null;  
            $dates_array[0]["fldsRcStatus"]=$error_msg;
            $dates_array[0]["fldsFlg"]="N";
            $woHeaderDataList = null;
            $orderTrcList = null;
            $plancList=null;
            $productionList=null;
            $packingList=null;
            $dispatchList=null;
        }
        $operatorList = $this->genOperator(null);
        return view('general/OrderTracking/OrderTracking', ['woHeaderDataList' => $woHeaderDataList,
                                                              'orderTrcList' => $orderTrcList,
                                                              'dates_array' => $dates_array,                                        
                                                              'searchingVals' => $constraints,
                                                              'operatorList' => $operatorList,
                                                              'operator' => $operator]);
    }
}
