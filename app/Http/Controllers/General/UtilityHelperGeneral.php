<?php

namespace Larashop\Http\Controllers\General;

use DB;
use Auth;
use Mail;
use Carbon\Carbon;
use Larashop\Models\User;
use Larashop\Models\General\Employee;
use Larashop\Models\General\WorkOrderHeader;
use Larashop\Models\General\WorkOrderLine;
use Larashop\Models\General\LabelReference;
use Larashop\Models\General\Machine;
use Larashop\Models\General\PlanBaseTable;
use Larashop\Models\General\PlnBoard;
use Larashop\Models\General\OrderTracking;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

trait UtilityHelperGeneral
{
	public function createSystemLogs($action){
    		
        $this->insertRecords('system_logs',array('created_by'=>Auth::user()->id,
                                            'updated_by'=>Auth::user()->id,
                                            'action'=>$action,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')),false);
    }
    
    public function insertRecords($tableName,$data,$isBulk){
        if($isBulk)
            return DB::table($tableName)->insert($data);
        else
            return DB::table($tableName)->insertGetId($data);
    }

   public function updateRecords($tableName,$idList,$data){
        return DB::table($tableName)
                    ->where('id', $idList)
                    ->update($data);
    }

    public function getLastRecord($modelName){
        if($modelName==='Employee'){
            return Employee::orderBy('id', 'desc')->first();

        }
        return null;
    }

    public function generateGender($chosen){
        $genderDefaultList = array('Male','Female');
        $genderList = array();
        if(!is_null($chosen)){
            $genderList[] = $chosen;
            foreach ($genderDefaultList as $gen) {
               if($chosen !== $gen)
                    $genderList[] = $gen;
            }
            return $genderList;
        }else{
            return $genderDefaultList;
        }
    }

    public function genTableType($chosen){
        $defaultList = array('Region','Benchmark');
        $lst = array();
        if(!is_null($chosen)){
            $lst[] = $chosen;
            foreach ($defaultList as $gen) {
               if($chosen !== $gen)
                    $lst[] = $gen;
            }
            return $lst;
        }else{
            return $defaultList;
        }
    }

    public function getWoArrayList($main_workorder_id){ 
        $dataLst = WorkOrderHeader::where('main_workorder_id', '=', $main_workorder_id)->get();
        $array_list = array();        
        foreach($dataLst  as $row) {
           $id=$row->id;
           array_push($array_list, $id);
        } 
        return $array_list; 
    }

    public function getWoTotalQty($woArrayList){         
        $dataLst = DB::table('work_order_lines')
                    ->select(DB::raw('sum(quantity) as qty')) 
                    ->where('deleted_at', '=', null)
                    ->whereIn('work_order_header_id',$woArrayList)
                    ->first();
        $qty=0;    
        if($dataLst->qty){
            $qty= $dataLst->qty;
        }
        return $qty; 
    }

    public function getWoQty($workorderheaders_id){         
        $dataLst = DB::table('work_order_lines')
                    ->select(DB::raw('sum(quantity) as qty'))
                    ->where('work_order_header_id', '=', $workorderheaders_id) 
                    ->where('deleted_at', '=', null)                  
                    ->first();
        $qty=0;    
        if($dataLst->qty){
            $qty= $dataLst->qty;
        }
        return $qty; 
    }

    public function getProduceQty($woArrayList){ 
        $data = DB::table('printings')
                    ->select(DB::raw('sum(quantity) as qty'))                     
                    ->whereIn('work_order_header_id',$woArrayList)
                    ->where('deleted_at', '=', null)
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    } 

    public function getAqlQty($woArrayList){ 
        $data = DB::table('aqls')
                    ->select(DB::raw('sum(quantity) as qty'))                     
                    ->whereIn('work_order_header_id',$woArrayList)
                    ->where('deleted_at', '=', null)
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    }

    public function getPackedQty($woArrayList){ 
        $data = DB::table('packings')
                    ->select(DB::raw('sum(quantity) as qty'))                     
                    ->whereIn('work_order_header_id',$woArrayList)
                    ->where('deleted_at', '=', null)
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    }

    public function getProduceCuttingsQty($woArrayList){ 
        $data = DB::table('cuttings')
                    ->select(DB::raw('sum(quantity) as qty'))                     
                    ->whereIn('work_order_header_id',$woArrayList)
                    ->where('deleted_at', '=', null)
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    }

    public function getWoProduceQty($workorderheaders_id){ 
        $data = DB::table('printings')
                    ->select(DB::raw('sum(quantity) as qty'))
                    ->where('work_order_header_id', '=', $workorderheaders_id) 
                    ->where('deleted_at', '=', null)                   
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    } 


    public function getWoProduceCutQty($workorderheaders_id){ 
        $data = DB::table('cuttings')
                    ->select(DB::raw('sum(quantity) as qty'))
                    ->where('work_order_header_id', '=', $workorderheaders_id) 
                    ->where('deleted_at', '=', null)                   
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    } 

    public function getRateFileIdRotary($company, $location, $machine_type_id, $table_type, $dep_id){   
        $dataObj = DB::table('mrn_rate_file_rotaries')
                    ->select('id')
                    ->where('company_id', '=', $company)
                    ->where('location_id', '=', $location)
                    ->where('machine_type_id', '=', $machine_type_id)
                    ->where('table_type', '=', $table_type)
                    ->where('department_id', '=', $dep_id)
                    ->where('deleted_at', '=', null)                   
                    ->first();
                    
        $val=0;    
        if($dataObj){
            $val= $dataObj->id;
        }       
        return $val; 
    }

    public function getRunningWasteRotary($rate_file_id, $total_qty, $sbc_id){         
        $dataObj = DB::table('running_waste_rotaries')
                    ->select('percentage')
                    ->where('mrn_rate_file_rotaries_id', '=', $rate_file_id)
                    ->where('from_ord_qty', '<', $total_qty)
                    ->where('to_ord_qty', '>=', $total_qty)
                    ->where('substrate_category_id', '=', $sbc_id)                    
                    ->where('deleted_at', '=', null)                   
                    ->first();                   
        $val=0;
        if($dataObj){
            $val= $dataObj->percentage;
        }
        return $val; 
    } 

    public function getCfWasteRotary($rate_file_id, $fold_type){         
        $dataObj = DB::table('cutting_waste_rotaries')
                    ->select('pcs')
                    ->where('mrn_rate_file_rotaries_id', '=', $rate_file_id)
                    ->where('cutting_methods_id', '=', $fold_type)                                      
                    ->where('deleted_at', '=', null)                   
                    ->first();                    
        $val=0;
        if($dataObj){
            $val= $dataObj->pcs;
        }
        return $val; 
    } 

    public function getAddToCustomerRotary($rate_file_id, $total_qty){         
        $dataObj = DB::table('additions_to_cus_rotaries')
                    ->select('percentage')
                    ->where('mrn_rate_file_rotaries_id', '=', $rate_file_id)
                    ->where('from_ord_qty', '<', $total_qty)
                    ->where('to_ord_qty', '>=', $total_qty)                                 
                    ->where('deleted_at', '=', null)                   
                    ->first();                   
        $val=0;
        if($dataObj){
            $val= $dataObj->percentage;
        }
        return $val; 
    } 

    public function getOeeEfficiency($table_type, $date_diff, $now, $departments_id){
        $data_arre=[];
        for($i=0; $i<$date_diff; $i++){              
            $date = date('Y-m-d', strtotime(Carbon::parse($now)->addDays($i)));            
            $dataObj = DB::table('mrn_header_rotaries') 
                            ->leftJoin('work_order_headers', 'mrn_header_rotaries.work_order_header_id', '=', 'work_order_headers.id')
                            ->select(DB::raw('sum(mrn_header_rotaries.cal_oee_percentage) as pre'), DB::raw('count(mrn_header_rotaries.cal_oee_percentage) as cnt'), DB::raw('mrn_header_rotaries.date'))       
                            ->where('mrn_header_rotaries.date','=', $date) 
                            ->where('mrn_header_rotaries.table_type','=', $table_type)  
                            ->where('work_order_headers.department_id','=', $departments_id)               
                            ->where('mrn_header_rotaries.deleted_at', '=', null)                   
                            ->first();
                           
            $precentage=0;$pre=0;$cnt=0;$date_a=null;
            if($dataObj->pre!=null){
                $pre=$dataObj->pre;                
                $cnt=$dataObj->cnt;  
                $date_a=$dataObj->date;
                $precentage=round(($pre/$cnt),2);
                $data_arre[$date_a]=$precentage;
            }else{               
                $data_arre[$date]=0;
            }     
        } 

        return $data_arre;
    } 

    public function getQualityEfficiency($table_type, $date_diff, $now, $departments_id){
        $data_arre=[];
        for($i=0; $i<$date_diff; $i++){              
            $date = date('Y-m-d', strtotime(Carbon::parse($now)->addDays($i)));            
            $dataObj = DB::table('mrn_header_rotaries')
                            ->leftJoin('work_order_headers', 'mrn_header_rotaries.work_order_header_id', '=', 'work_order_headers.id') 
                            ->select(DB::raw('sum(mrn_header_rotaries.cal_quality_percentage) as pre'), DB::raw('count(mrn_header_rotaries.cal_quality_percentage) as cnt'), DB::raw('mrn_header_rotaries.date'))       
                            ->where('mrn_header_rotaries.date','=', $date) 
                            ->where('mrn_header_rotaries.table_type','=', $table_type) 
                            ->where('work_order_headers.department_id','=', $departments_id)                
                            ->where('mrn_header_rotaries.deleted_at', '=', null)                   
                            ->first();
                           
            $precentage=0;$pre=0;$cnt=0;$date_a=null;
            if($dataObj->pre!=null){
                $pre=$dataObj->pre;                
                $cnt=$dataObj->cnt;  
                $date_a=$dataObj->date;
                $precentage=round(($pre/$cnt),2);
                $data_arre[$date_a]=$precentage;
            }else{               
                $data_arre[$date]=0;
            }     
        } 

        return $data_arre;
    }

    public function getEffTrgetArray($date_diff, $now){
        $data_arre=[];
        for($i=0; $i<$date_diff; $i++){              
            $date = date('Y-m-d', strtotime(Carbon::parse($now)->addDays($i)));            
            $data_arre[$date]=100;     
        } 

        return $data_arre;
    }

    public function getDateArray($date_diff, $now){
        $data_arre=[];
        for($i=0; $i<$date_diff; $i++){              
            $date = date('Y-m-d', strtotime(Carbon::parse($now)->addDays($i)));            
            $data_arre[$date]=$date;     
        } 

        return $data_arre;
    }

    public function genShift($chosen){
        $defaultLst = array('D','N');
        $lst = array();
        if(!is_null($chosen)){
            $lst[] = $chosen;
            foreach ($defaultLst as $gen) {
               if($chosen !== $gen)
                    $lst[] = $gen;
            }
            return $lst;
        }else{
            return $defaultLst;
        }
    }

    public function getDeptName($department_id){ 
        $data = DB::table('departments')
                    ->where('id', $department_id)
                    ->where('deleted_at', '=', null)
                    ->first();
        $name="";        
        if($data){
            $name= $data->name;
        }
        return $name; 
    }

    public function getEmpDetails($employees_id){      
        
        $data = Employee::where('deleted_at', '=', null)
                     ->where('id', '=', $employees_id)                                     
                     ->first();
        return $data;
    }

    public function getMachineDetails($machine_id){        
       $data = Machine::where('id', '=', $machine_id)->where('deleted_at', '=', null)->first();
       return $data;
    }

    public function getWoQtySizeWise($wo_id, $l_size_no){ 
        $data = DB::table('work_order_lines')                 
                    ->where('deleted_at', '=', null)
                    ->where('size', '=', $l_size_no)
                    ->where('work_order_header_id','=', $wo_id)
                    ->first();
        $qty=0; 
        if($data){
            $qty= $data->quantity;
        }
        return $qty; 
    }

    public function getPrintQtySizeWise($wo_id, $l_size_no){ 
        $data = DB::table('printings')
                    ->select(DB::raw('sum(quantity) as qty'))                   
                    ->where('deleted_at', '=', null)
                    ->where('size', '=', $l_size_no)
                    ->where('work_order_header_id','=', $wo_id)
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    }

    public function getAqlQtySizeWise($wo_id, $l_size_no){ 
        $data = DB::table('aqls')
                    ->select(DB::raw('sum(quantity) as qty'))                   
                    ->where('deleted_at', '=', null)
                    ->where('size', '=', $l_size_no)
                    ->where('work_order_header_id','=', $wo_id)
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    }

    public function getPackingQtySizeWise($wo_id, $l_size_no){ 
        $data = DB::table('packings')
                    ->select(DB::raw('sum(quantity) as qty'))                   
                    ->where('deleted_at', '=', null)
                    ->where('size', '=', $l_size_no)
                    ->where('work_order_header_id','=', $wo_id)
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    }

    public function getCuttingQtySizeWise($wo_id, $l_size_no){ 
        $data = DB::table('cuttings')
                    ->select(DB::raw('sum(quantity) as qty'))                   
                    ->where('deleted_at', '=', null)
                    ->where('size', '=', $l_size_no)
                    ->where('work_order_header_id','=', $wo_id)
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    }

    public function getWoQtyGen($wo_id){

        $data = DB::table('work_order_lines')
                    ->select(DB::raw('sum(quantity) as woQty')) 
                    ->where('deleted_at', '=', null)
                    ->where('work_order_header_id', '=' ,$wo_id)
                    ->first();
        $qty=0;
    
        if($data->woQty){
            $qty= $data->woQty;
        }
        return $qty; 
    } 

     public function getAqlkQtyGen($wo_id){ 
        $data = DB::table('aqls')
                    ->select(DB::raw('sum(quantity) as qty')) 
                    ->where('deleted_at', '=', null)
                    ->where('work_order_header_id', '=' ,$wo_id)
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    }

    public function getPackQtyGen($wo_id){ 
        $data = DB::table('packings')
                    ->select(DB::raw('sum(quantity) as qty')) 
                    ->where('deleted_at', '=', null)
                    ->where('work_order_header_id', '=' ,$wo_id)
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    }

    public function getPrintQtyGen($wo_id){ 
        $data = DB::table('printings')
                    ->select(DB::raw('sum(quantity) as qty')) 
                    ->where('deleted_at', '=', null)
                    ->where('work_order_header_id', '=' ,$wo_id)
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    }

    public function getCutQtyGen($wo_id){ 
        $data = DB::table('cuttings')
                    ->select(DB::raw('sum(quantity) as qty')) 
                    ->where('deleted_at', '=', null)
                    ->where('work_order_header_id', '=' ,$wo_id)
                    ->first();
        $qty=0; 
        if($data->qty){
            $qty= $data->qty;
        }
        return $qty;
    }

    public function genOperator($chosen){
        $genderDefaultList = array('%','=');
        $genderList = array();
        if(!is_null($chosen)){
            $genderList[] = $chosen;
            foreach ($genderDefaultList as $gen) {
               if($chosen !== $gen)
                    $genderList[] = $gen;
            }
            return $genderList;
        }else{
            return $genderDefaultList;
        }
    }

    public function getMainWoIdArrayLst($main_workorder_id, $main_workorder){
        $main_wo_array_list=[]; 
        $main_wo_array_list[$main_workorder_id]=$main_workorder;
        $wohLst = WorkOrderHeader::where('main_workorder_id', '=', $main_workorder_id)->get();
        foreach($wohLst  as $wohRow) {
          $header_id=$wohRow->id;
          $workorderno=$wohRow->workorder_no;
          if($main_workorder!=$workorderno){
            $main_wo_array_list[$header_id]=$workorderno;
          }
        }
        return $main_wo_array_list;
    }

    public function getProductionQty($end, $now, $date_diff, $departments_id){
        $prd_qty=[];
        for($i=0; $i<$date_diff; $i++){              
            $date = date('Y-m-d', strtotime(Carbon::parse($now)->addDays($i)));          
            $dataObj = DB::table('printings') 
                            ->select(DB::raw('sum(quantity) as Qty, date'))       
                            ->where('date','=', $date)
                            ->where('department_id','=',$departments_id)  
                            ->where('deleted_at','=', null)                   
                            ->first();
            
            if($dataObj->Qty!=null){  
                $prd_qty[$date]=$dataObj->Qty;
            }else{
                $prd_qty[$date]=0;
            }  
        } 
        return $prd_qty;
    }

    public function getCuttingQty($end, $now, $date_diff, $departments_id){
        $cut_qty=[];
        for($i=0; $i<$date_diff; $i++){              
            $date = date('Y-m-d', strtotime(Carbon::parse($now)->addDays($i)));          
            $dataObj = DB::table('cuttings') 
                            ->select(DB::raw('sum(quantity) as Qty, date'))       
                            ->where('date','=', $date)
                            ->where('department_id','=',$departments_id)  
                            ->where('deleted_at','=', null)                   
                            ->first();
            
            if($dataObj->Qty!=null){  
                $cut_qty[$date]=$dataObj->Qty;
            }else{
                $cut_qty[$date]=0;
            }  
        } 
        return $cut_qty;
    }

    public function genLabelType($chosen){
        $genderDefaultList = array('A','B','C');
        $genderList = array();
        if(!is_null($chosen)){
            $genderList[] = $chosen;
            foreach ($genderDefaultList as $gen) {
               if($chosen !== $gen)
                    $genderList[] = $gen;
            }
            return $genderList;
        }else{
            return $genderDefaultList;
        }
    }

    public function getWoNo($id){        
        $data = DB::table('work_order_headers')                         
                ->where('id', '=', $id)                        
                ->first();

        return $data;      
    }

    public function getWoNoX($main_workorder_id){        
        $data = DB::table('work_order_headers')                         
                ->where('id', '=', $main_workorder_id)                        
                ->first();
                
        return $data;         
    }

    public function updateRecordsMainDeliveryDate($tableName,$idList,$data){
        return DB::table($tableName)
                    ->where('main_workorder_id', $idList)
                    ->update($data);
    }

    public function getPlnBaseTableData($dep_id){       
        
        $data = PlanBaseTable::where('deleted_at', null)
                    ->where('department_id','=', $dep_id)
                    ->where('company_id','=', Auth::user()->locations->companies->id) 
                    ->where('location_id','=', Auth::user()->locations->id)   
                    ->orderBy('id', 'desc')
                    ->first(); 

        return $data;
    }

    public function getPlanDate($dep_id){       
    
        $data = PlanBaseTable::where('deleted_at', null)
                    ->where('department_id','=', $dep_id)                     
                    ->where('company_id','=', Auth::user()->locations->companies->id) 
                    ->where('location_id','=', Auth::user()->locations->id) 
                    ->orderBy('id', 'desc')
                    ->first();
                
        if($data){                    
          $date = date('Y-m-d', strtotime(Carbon::parse($data->date)->addDays(1)));
        }
        $i = 0;         
        for($i=0; $i<100; $i++) {
            $plandatenew = date('Y-m-d', strtotime(Carbon::parse($date)->addDays($i)));
            $holidays = DB::table('holidays')
                               ->leftJoin('day_types', 'holidays.day_types_id', '=', 'day_types.id')
                               ->select('holidays.*', 'day_types.name as daytype_name', 'day_types.colorpicker_id as colorpicker_id')
                               ->where('holidays.date', '=', $plandatenew )
                               ->where('holidays.companies_id','=', Auth::user()->locations->companies->id) 
                               ->where('holidays.deleted_at', '=', null)
                               ->first();

            if(!$holidays){
               $short_date=strtoupper(date('D', strtotime($plandatenew)));
                if($short_date=="SAT" || $short_date=="SUN"){                     
                  
                }else{
                    break 1;
                }
            }
        }
        return $plandatenew;
    }

    public function getNextPlanDate($plandate, $yes_no){              
        $i = 0;         
        for($i=0; $i<100; $i++) {
            $plandatenew = date('Y-m-d', strtotime(Carbon::parse($plandate)->addDays($i)));
            $holidays = DB::table('holidays')
                               ->leftJoin('day_types', 'holidays.day_types_id', '=', 'day_types.id')
                               ->select('holidays.*', 'day_types.name as daytype_name', 'day_types.colorpicker_id as colorpicker_id')
                               ->where('holidays.date', '=', $plandatenew )
                               ->where('holidays.companies_id','=', Auth::user()->locations->companies->id) 
                               ->where('holidays.deleted_at', '=', null)
                               ->first();

            if(!$holidays){
                $short_date=strtoupper(date('D', strtotime($plandatenew)));

                if($yes_no=="Yes"){
                    if($short_date=="SUN"){                     
                      
                    }else{
                        break 1;
                    }      
                }else{
                    if($short_date=="SAT" || $short_date=="SUN"){                     
                      
                    }else{
                        break 1;
                    }                
                }
                
            }
        }
        return $plandatenew;
    }

    public function genProcessStatus($chosen){
        $processDefaultList = array('Process Plan','Freeze Plan');
        $processList = array();
        if(!is_null($chosen)){
            $processList[] = $chosen;
            foreach ($processDefaultList as $gen) {
               if($chosen !== $gen)
                    $processList[] = $gen;
            }
            return $processList;
        }else{
            return $processDefaultList;
        }
    }

    public function getLastPlanedDate($dep_id){       
        
        $date=null;
        $data = PlanBaseTable::where('deleted_at', null)
                    ->where('department_id','=', $dep_id) 
                    ->where('freeze_status','=', 1) 
                    ->where('company_id','=', Auth::user()->locations->companies->id) 
                    ->where('location_id','=', Auth::user()->locations->id) 
                    ->orderBy('id', 'desc')
                    ->first();
                
        if($data){                    
          $date = $data->date;
        }               
        return $date;
    }

    public function getMaxDeliveryDate($dep_id){
        $data = WorkOrderHeader::where('deleted_at', null)
                    ->where('department_id','=', $dep_id)  
                    ->where('company_id','=', Auth::user()->locations->companies->id) 
                    ->where('location_id','=', Auth::user()->locations->id) 
                    ->orderBy('delivery_date', 'desc')
                    ->first();                   
            
        $date=null;
        if($data){
           $date=$data->delivery_date;          
        }
        return $date;
    }

    public function getBaseData($dep_id){       
    
        $data = PlanBaseTable::where('deleted_at', null)
                    ->where('department_id','=', $dep_id) 
                    ->where('company_id','=', Auth::user()->locations->companies->id) 
                    ->where('location_id','=', Auth::user()->locations->id) 
                    ->orderBy('id', 'desc')
                    ->first();
        
        return $data;
    }

    public function getNoOfSizeAfterBatch($main_wo_id){  
        $data=0;
        $size_array_list = array();
        $size_array = []; 
        $j=0;
        $wohLst = WorkOrderHeader::where('main_workorder_id', '=', $main_wo_id)->get();
        // echo "main_wo_id : ".$main_wo_id;
        // echo "</br>";
        // dd($wohLst);

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
            } 
            $j++;               
        }

        $data=count($size_array_list)-1;       
        if($data<=0){
            $data=1;
        }  
        if($data==0){
            $data=1;
        }        
        return $data;
    }

    public function getCalStandardTime($main_wo_id, $machine_type_id, $table_type, $dep_id, $no_front_colour, $no_back_colour, $no_of_sizes_after_batch, $total_qty, $lenght, $fold_type, $sbc_id, $no_of_tapes){  
        
        // echo "main_wo_id : ".$main_wo_id;
        // echo "</br>";
        // echo "machine_type_id : ".$machine_type_id;
        // echo "</br>";
        // echo "table_type : ".$table_type;
        // echo "</br>";
        // echo "dep_id : ".$dep_id;
        // echo "</br>";
        // echo "no_front_colour : ".$no_front_colour;
        // echo "</br>";
        // echo "no_back_colour : ".$no_back_colour;
        // echo "</br>";
        // echo "no_of_sizes_after_batch : ".$no_of_sizes_after_batch;
        // echo "</br>";
        // echo "total_qty : ".$total_qty;
        // echo "</br>";
        // echo "lenght : ".$lenght;
        // echo "</br>";
        // echo "fold_type : ".$fold_type;
        // echo "</br>";
        // echo "sbc_id : ".$sbc_id;
        // echo "</br>";
        // echo "no_of_tapes : ".$no_of_tapes; 
        // echo "</br>";       
        // dd("dddddddddddddd");

        $data=0;
        $company=Auth::user()->locations->companies->id;
        $location=Auth::user()->locations->id;

        $rate_file_id=$this->getRateFileIdRotary($company, $location, $machine_type_id, $table_type, $dep_id);
        $dataObjRateFile = DB::table('mrn_rate_file_rotaries')
                            ->where('id', '=', $rate_file_id)                    
                            ->where('deleted_at', '=', null)                   
                            ->first();

        $ref_changes_min=0;$setup_time_per_tape=0;$setup_time_per_colour_front=0;
        $setup_time_per_colour_back=0;$setup_time_plate_change=0;$setup_mtr_per_colour_front=0;
        $setup_mtr_per_colour_back=0;$setup_mtr_plate_change=0;
        $running_waste_percentage=0;$cf_waste_pcs=0;$add_to_customer_percentage=0;
        $machine_speed_mrt_per_hrs=0;$cal_ribbon_mtr=0;
        $cal_cut_fold_mtr=0;$cal_set_up_mtr=0;$cal_plate_change_mtr=0;
        $cal_running_waste_mtr=0;$qty_per_Size=0;$qty_per_Size_packing=0;
        $cal_additions_mtr=0;$total_material_issued_mtr=0;$total_time_setup_time_duration=0;
        $total_time_for_plate_changes=0;$cal_running_time=0;$total_standard_time_for_job=0;       

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

        $cal_ribbon_mtr=round(($total_qty*$lenght)/1000,2);
        $cal_cut_fold_mtr=round(($cf_waste_pcs*$lenght)/1000,2);
        $cal_set_up_mtr = ($setup_mtr_per_colour_front*$no_front_colour)+($setup_mtr_per_colour_back*$no_back_colour);
        $cal_plate_change_mtr=(($no_of_sizes_after_batch)*$setup_mtr_plate_change);
        $cal_running_waste_mtr=round((($running_waste_percentage*$total_qty*$lenght)/1000)/100,2);
        $qty_per_Size=round(($total_qty/$no_of_sizes_after_batch));
        $qty_per_Size_packing=round(($total_qty/$no_of_sizes_after_batch));
        $cal_additions_mtr=round((($lenght*$no_of_sizes_after_batch*$add_to_customer_percentage*$qty_per_Size)/1000)/100,2);
        $total_material_issued_mtr=$cal_ribbon_mtr+$cal_cut_fold_mtr+$cal_set_up_mtr+$cal_plate_change_mtr+$cal_running_waste_mtr+$cal_additions_mtr;

        $total_time_for_plate_changes=(($no_of_sizes_after_batch)*$setup_time_plate_change);
        $cal_running_time=(($total_material_issued_mtr-($cal_set_up_mtr+$cal_plate_change_mtr))/($machine_speed_mrt_per_hrs/60)); 
        $total_time_setup_time_duration=(($no_of_tapes*$setup_time_per_tape)+($setup_time_per_colour_front*$no_front_colour)+($setup_time_per_colour_back*$no_back_colour));

        $total_standard_time_for_job=$total_time_setup_time_duration+$total_time_for_plate_changes+$cal_running_time;
        if($total_standard_time_for_job<=0){
            return 1;

        }else{
            return (int)$total_standard_time_for_job+1;    
        }
        
    }

    public function getMachineLst($department_id, $no_front_colour, $no_back_colour, $machine_type_id){ 

        // echo "department_id : ".$department_id;
        // echo "</br>";
        // echo "no_front_colour : ".$no_front_colour;
        // echo "</br>";
        // echo "no_back_colour : ".$no_back_colour;
        // echo "</br>";
        // echo "machine_type_id : ".$machine_type_id;
        // echo "</br>";
        // echo "Country : ".Auth::user()->locations->companies->id;
        // echo "</br>";
        // echo "location : ".Auth::user()->locations->id;
       
        $data = Machine::where('deleted_at', null)
                    ->where('department_id','=', $department_id) 
                    ->where('machine_type_id','=', $machine_type_id) 
                    ->where('condition_id','=', 1) 
                    ->where('machine_category_id','=', 1) 
                    ->where('isActive','=', 1) 
                    ->where('no_of_colour_front','>=', $no_front_colour) 
                    ->where('no_of_colour_back','>=', $no_back_colour) 
                    ->where('company_id','=', Auth::user()->locations->companies->id) 
                    ->where('location_id','=', Auth::user()->locations->id) 
                    ->orderBy('no_of_colour_front', 'asc')
                    ->get();

        return $data; 
    }

    public function getMcHldStatus($machine_id, $plandate, $shift, $department_id){       
        $data = DB::table('hold_machines')
                    ->where('machine_id', '=', $machine_id)
                    ->where('department_id','=', $department_id)
                    ->where('date', '=', $plandate)
                    ->where('shift', '=',$shift)
                    ->where('company_id','=', Auth::user()->locations->companies->id) 
                    ->where('location_id','=', Auth::user()->locations->id) 
                    ->where('deleted_at', '=', null)
                    ->first();

        $status="N";
        if($data){
            $status="Y"; 
        }                    
        return $status;
    }

    public function getAvailableMinutes($machine_id, $plandate, $shift, $shift_working_minute, $additional_mnt){      
        
        $data = DB::table('pln_boards')
                    ->select(DB::raw('sum(pln_mnt) as plnMnt'))   
                    ->where('machine_id', '=', $machine_id)
                    ->where('pln_date', '=', $plandate)
                    ->where('pln_shift', '=',$shift)
                    ->where('deleted_at', '=', null)
                    ->first();
        $plnMnt=0;
        $availableMinutes=0;
        if($data->plnMnt){
          $plnMnt= $data->plnMnt; 
        }        
        if($plnMnt>=$shift_working_minute){
          $availableMinutes=0;
        }else{          
          $availableMinutes=(($shift_working_minute+$additional_mnt)-$plnMnt);
        }
        return $availableMinutes;
    }

    public function getPlanedQty($main_wo_id, $base_date){ 

        $data = DB::table('pln_boards')
                    ->select(DB::raw('sum(pln_qty) as plnQty')) 
                    ->where('work_order_header_id','=', $main_wo_id)
                    ->where('pln_date','>=', $base_date)
                    ->where('deleted_at', '=', NULL)
                    ->first();
        $pln_qty=0;         
        if($data->plnQty){
            $pln_qty= $data->plnQty;
        }

        return $pln_qty; 
    }

    public function getPreviousPlnMc($label_reference_id){ 

        $data = DB::table('pln_boards')
                    ->leftJoin('work_order_headers', 'pln_boards.work_order_header_id', '=', 'work_order_headers.id')
                    ->select('pln_boards.machine_id as machine_id')
                    ->where('work_order_headers.label_reference_id','=', $label_reference_id)
                    ->where('work_order_headers.deleted_at', '=', null)
                    ->orderBy('pln_boards.id', 'desc')
                    ->first();
        $machine_id=null;         
        if($data){
            $machine_id= $data->machine_id;
        }

        return $machine_id; 
    }

    public function genYesNo($chosen){
        $planDefaultList = array('Yes','No');
        $planList = array();
        if(!is_null($chosen)){
            $planList[] = $chosen;
            foreach ($planDefaultList as $gen) {
               if($chosen !== $gen)
                    $planList[] = $gen;
            }
            return $planList;
        }else{
            return $planDefaultList;
        }
    }

    public function getPlanedMntMachine($machine_id, $plandate){ 

        $data = DB::table('pln_boards')
                    ->select(DB::raw('sum(pln_mnt) as plnMnt')) 
                    ->where('machine_id','=', $machine_id)
                    ->where('pln_date','=', $plandate)
                    ->where('deleted_at', '=', NULL)
                    ->first();
        $pln_mnt=0;         
        if($data->plnMnt){
            $pln_mnt= $data->plnMnt;
        }

        return $pln_mnt; 
    }

    public function clrPlanBoard($dep_id, $base_date, $yes_no){         
        $data=PlnBoard::leftJoin('work_order_headers', 'pln_boards.work_order_header_id', '=', 'work_order_headers.id') 
                    ->where('work_order_headers.department_id','=', $dep_id)
                    ->where('pln_boards.pln_date', '>=', $base_date) 
                    ->where('work_order_headers.company_id','=', Auth::user()->locations->companies->id) 
                    ->where('work_order_headers.location_id','=', Auth::user()->locations->id)                   
                    ->forceDelete();
        
    }   


    public function getMaxPlanDate($dep_id){

        $data=PlnBoard::select('pln_boards.pln_date')
                    ->leftJoin('work_order_headers', 'pln_boards.work_order_header_id', '=', 'work_order_headers.id') 
                    ->where('work_order_headers.department_id','=', $dep_id)
                    ->where('work_order_headers.company_id','=', Auth::user()->locations->companies->id) 
                    ->where('work_order_headers.location_id','=', Auth::user()->locations->id) 
                    ->orderBy('pln_boards.pln_date', 'desc')
                    ->first();    
            
        $date=null;
        if($data->pln_date){
            $date=$data->pln_date;
        }else{
            $now = Carbon::now(); 
            $date = date('Y-m-d', strtotime(Carbon::parse($now)));           
        }        
        return $date;
    } 

    public function getCurrentLocation($workorderheaders_id){
        $data = DB::table('work_order_headers')
                        ->where('id', '=', $workorderheaders_id)
                        ->first();       
        $current_location="N/A";
        if($data){
            $workorderno = $data->workorder_no;
            $dataTrc = OrderTracking::where('workorder_no', '=', $workorderno)->orderBy('id', 'desc')->first();
            if($dataTrc!=null){
                $current_dep=$dataTrc->department_id;
                if($current_dep!=null){
                    $current_location=$dataTrc->departments->code;
                }               
            }
        }

        return $current_location;
    }

    public function getWoArray($workorderheaders_id, $main_workorder_no){
        $data = WorkOrderHeader::where('main_workorder_id', '=', $workorderheaders_id)->get();
        $workorder_list=$main_workorder_no;
        foreach($data as $row){
            $workorderno = $row->workorder_no;
            if($main_workorder_no!=$workorderno){
                $workorder_list=$workorder_list.'/'.$workorderno;
            }             
        } 
       
        return $workorder_list;
    }
}
