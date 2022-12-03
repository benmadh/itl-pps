<?php

namespace Larashop\Http\Controllers\Rotary;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Larashop\Models\General\LabelReference;
use Larashop\Models\General\WorkOrderHeader;
use Larashop\Models\General\WorkOrderLine;
use Larashop\Models\General\Department;
use Larashop\Models\General\Machine;
use Larashop\Models\General\Holiday;

use Carbon\Carbon;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;
Use Larashop\Http\Controllers\General\UtilityHelperGeneral;

class ProcessRotaryController extends Controller
{
  use UtilityHelperGeneral;

    public function __construct(){
        $this->middleware('permission:process_rotary_access_allow');       
    }    

    public function index(){       
        
      $dep_id=12;
      $error_des=null; 
      $str_time =  null;
      $end_time = null;      
      $current_date =  date('Y-m-d');      
      $baseTableData = $this->getPlnBaseTableData($dep_id);  
      $searchingVals = ['process_id' => "Process Plan", 'yes_no' => "No"];    
      if($baseTableData){
        $base_date=$baseTableData->date;
        $freeze_status=$baseTableData->freeze_status;
        $plan_date = $this->getPlanDate($dep_id);
        $error_des="Notification, Process date :- ".$base_date." ****  Next Process date :- ".$plan_date;
       
        $processList = $this->genProcessStatus(null);
        $yesNoList = $this->genYesNo(null); 
        $last_planed_date = $this->getLastPlanedDate($dep_id); 
        $params = [
            'title' => 'Planning Process for Rotary', 
            'last_planed_date' => $last_planed_date,
            'next_plan_date' => $plan_date,
            'error_des' => $error_des,
            'current_freeze_status' => $freeze_status,
            'processList' => $processList,
            'searchingVals' => $searchingVals,
            'yesNoList' => $yesNoList,
            'str_time' => $str_time,
            'end_time' => $end_time,
        ];
        return view('rotary.process.process')->with($params);

      }else{
        $error_des="Please Contact System Administrator";
        dd($error_des);
      }
    }

    public function process(Request $request){ 
      $dep_id=12;
      $error_des=null; 
      $str_time =  date('Y-m-d H:i:s');    
      $current_date =  date('Y-m-d'); 
      $working_hours_per_day=15;
      $working_minut_per_day=900; 
      $s_d_hours=7.5;
      $s_n_hours=7.5;
      $shift_working_minute=450;
      $additional_mnt=30;
      $process_id=$request->input('process_id'); 
      $yes_no=$request->input('yes_no'); 
      $base_date = $this->getPlanDate($dep_id);
      $to_date = $this->getMaxDeliveryDate($dep_id);
      $baseDataLst = $this->getBaseData($dep_id);
      $base_table_id=$baseDataLst->id;
      $base_date=$baseDataLst->date;         
      
      $this->clrPlanBoard($dep_id, $base_date, $yes_no);

      if($process_id=='Process Plan'){   
        $data = DB::table('work_order_headers')
                      ->leftJoin('order_types', 'work_order_headers.order_type_id', '=', 'order_types.id')
                      ->leftJoin('label_references', 'work_order_headers.label_reference_id', '=', 'label_references.id')
                      ->leftJoin('customers', 'work_order_headers.customer_id', '=', 'customers.id')
                      ->select('work_order_headers.*', 'order_types.priority as order_priority', DB::raw('sum(wo_quantity) as wototquantity'))
                      ->where('work_order_headers.status_id','=', 1)
                      ->where('work_order_headers.print_status_id','=', 1)
                      ->where('work_order_headers.cutting_status_id','=', 1)
                      ->where('work_order_headers.packing_status_id','=', 1)
                      ->where('work_order_headers.despatch_status_id','=', 1)
                      //->where('work_order_headers.main_workorder_id','=', 434888)
                      ->where('work_order_headers.department_id','=', $dep_id) 
                      ->where('work_order_headers.company_id','=', Auth::user()->locations->companies->id)
                      ->where('work_order_headers.location_id','=', Auth::user()->locations->id)                    
                      ->where('work_order_headers.deleted_at','=', null)                                 
                      ->groupBy('work_order_headers.main_workorder_id')                                  
                      ->orderBy('work_order_headers.mwo_delivery_date', 'asc')   
                      ->orderBy('work_order_headers.order_type_id', 'asc') 
                      ->orderBy('work_order_headers.lenght', 'asc')
                      ->orderBy('work_order_headers.label_reference_id', 'asc')
                      ->get();
         
        foreach($data as $row){
          $wo_id = $row->id;
          $main_workorder_no = $row->main_workorder_no; 
          $main_wo_id = $row->main_workorder_id;
          $dataMainWo = WorkOrderHeader::where('deleted_at', '=', null)->where('id', '=', $main_wo_id)->first();
          $standard_produce_time=0;$no_of_sizes_after_batch=0;
          if($dataMainWo){
            $order_type_id= $dataMainWo->order_type_id;
            $department_id= $dataMainWo->department_id;
            $label_reference_id = $dataMainWo->label_reference_id;   
            $sbc_id = $dataMainWo->substrate_category_id;  
            $substrate_category = $dataMainWo->substrate_category;         
            $deliverydate= $dataMainWo->mwo_delivery_date;
            $no_front_colour=$dataMainWo->no_of_colors_front; 
            $no_back_colour=$dataMainWo->no_of_colors_back; 
            $lenght=$dataMainWo->lenght; 
            $fold_type=$dataMainWo->fold_type;
            $size_changes= $dataMainWo->size_changes;
            $time_batch_size_upd= $dataMainWo->time_batch_size_upd;            
            $no_of_tapes=1;
            $machine_type="Analogue";
            if($machine_type=="Digital"){
              $machine_type_id=1;
            }elseif($machine_type=="Analogue"){
                    $machine_type_id=2;
            }else{
              echo $main_workorder_no;
              dd("Missing Machine Type...");
            }            
            $table_type ="Region";
            $woArrayList=$this->getWoArrayList($main_wo_id);
            $total_qty=$this->getWoTotalQty($woArrayList);

            if($time_batch_size_upd=="N"){
              //Get Size Changes After Batching         
              $no_of_sizes_after_batch=$this->getNoOfSizeAfterBatch($main_wo_id);
              //cal standard time for this WO
              $standard_produce_time=$this->getCalStandardTime($main_wo_id, $machine_type_id, $table_type, $department_id, $no_front_colour, $no_back_colour, $no_of_sizes_after_batch, $total_qty, $lenght, $fold_type, $sbc_id, $no_of_tapes);

              $input['time_batch_size_upd'] = "Y";
              $input['no_of_sizes_after_batch'] = $no_of_sizes_after_batch;
              $input['standard_produce_time'] = $standard_produce_time;
              $this->updateRecords('work_order_headers',array($main_wo_id),$input); 
            }else{
              $no_of_sizes_after_batch= $dataMainWo->no_of_sizes_after_batch;
              $standard_produce_time= $dataMainWo->standard_produce_time;
            }

            $machineLst=$this->getMachineLst($department_id, $no_front_colour, $no_back_colour, $machine_type_id);
            $shiftList = $this->genShift(null);
            $mc_cnt=$machineLst->count();
            
            // $shift_working_minute*4;            
            foreach($machineLst as $mpl){
              $machine_id=$mpl->id;  
              $planed_mnt_machine=0;
              for ($i = 0; $i <= 60; $i++ ) { 
                $plandate = date('Y-m-d', strtotime(Carbon::parse($base_date)->addDays($i)));
                $plandate = $this->getNextPlanDate($plandate, $yes_no);
             
                $planed_mnt_machine=$this->getPlanedMntMachine($machine_id, $plandate); 
                if($planed_mnt_machine>=($shift_working_minute*2)){
                  break 1;
                }                
                foreach($shiftList as $shoftRow){
                  $shift=$shoftRow;  
                  $mc_hld_status=$this->getMcHldStatus($machine_id, $plandate, $shift, $department_id);
                  
                  if($mc_hld_status=="N"){
                    $available_mnt=$this->getAvailableMinutes($machine_id, $plandate, $shift, $shift_working_minute, $additional_mnt); 
                    $bal_qty=0;$pln_mnt=0;$upd_pln_mnt=0;$pln_qty=0;$verify_pln_qty=0;$totQty=0;                  
                    
                    if($available_mnt!=0){
                      $planedQty=$this->getPlanedQty($main_wo_id, $base_date);                     
                      $produceQty=$this->getProduceQty($woArrayList);                    
                      $bal_qty=($total_qty-($produceQty+$planedQty));                    
                      $pln_mnt=(int)(($standard_produce_time/$total_qty)*$bal_qty)+1;                  
                      if($pln_mnt >=$available_mnt){
                        $upd_pln_mnt= $available_mnt;
                      }else{
                        $upd_pln_mnt=$pln_mnt;
                      }         
                      $pln_qty=(int)(($total_qty/$standard_produce_time)*$upd_pln_mnt)+1;
                      //verified plan qty
                      if(($planedQty+$produceQty+$pln_qty)> $total_qty){
                        $verify_pln_qty=$bal_qty;
                      }else{
                        $verify_pln_qty=$pln_qty;
                      }

                      if($base_date>$deliverydate){
                        $plan_type="AL";
                      }else{
                        $plan_type="A";
                      }
                      
                      if($verify_pln_qty!=0){
                        $this->insertRecords('pln_boards',array('pln_date'=>$plandate,
                               'pln_shift'=>$shift,
                               'pln_mnt'=>$upd_pln_mnt,                                         
                               'pln_qty'=>$verify_pln_qty,
                               'wo_tot_mnt'=>$standard_produce_time,
                               'wo_tot_qty'=>$total_qty,
                               'plan_type'=>$plan_type,
                               'machine_id'=>$machine_id,
                               'work_order_header_id'=>$main_wo_id,
                               'company_id' =>Auth::user()->locations->companies->id,
                               'location_id' => Auth::user()->locations->id,             
                               'created_by'=>Auth::user()->id, 
                               'created_at' => date('Y-m-d H:i:s')),false);
                      }

                      $planedQty=$this->getPlanedQty($main_wo_id, $base_date);
                      $totQty=$planedQty+$produceQty;

                      if($total_qty<=$totQty){
                        break 3;
                      }                      
                    }                    
                  }
                  // echo "01";
                  // echo "</br>"; 
                }
                // echo "02";
                // echo "</br>"; 
              }
              // echo "03";
              // echo "</br>"; 
            }
            // echo "04";
            // echo "</br>"; 
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

            //dd($standard_produce_time);


          }else{
             echo $main_workorder_no;
             dd("Missing Main WO Details...");

          }

          
          // $wo_minut=(int)(($wototQuantity/1000)*$smv)+1;           
          // $cylinder_change_mnt=0; 
          
          // //**********************************            
          // $produceQty=$this->getProductionQtyNew($woArrayList);
          // $balanceWoQty=$this->getBalWoQtyNew($wototQuantity, $produceQty);            
          // $balanceWoMnt=$this->getCalBalMntNew($balanceWoQty, $smv);
          // $required_days=$this->getRequiredDays($balanceWoMnt, $shift_working_minute); 
          // $machinePriorityList = $this->getMachinPriorityList($labelreferences_id);
          // //********************************** 
          // $lead_days=$this->getCusLeadTimes($customerbranches_id, $labelreferences_id);
          // $base_date = $this->getNextPlanDateNew($dep_id);
          
          // if($lead_days!=0){
          //   $str_plan_date = date('Y-m-d', strtotime(Carbon::parse($deliverydate)->subDays($lead_days)));
          //   if($str_plan_date<$base_date){
          //     $str_plan_date=$base_date;
          //   }
          //   $base_date=$str_plan_date;                  
          // }

          // //Start Calculation Process           
          // if($balanceWoQty!=0){  
                          
          //   foreach($machinePriorityList as $mpl){ 
          //     $priority=$mpl["priority"];
          //     $machine_id=$mpl["machine_id"]; 
          //     for ($i = 0; $i <= 60; $i++ ) {                      
          //       $plandate = date('Y-m-d', strtotime(Carbon::parse($base_date)->addDays($i)));

          //       $plandate = $this->getNextPlanDatePrd($plandate);
          //       $last_plan_referance=$this->getLastReferance($machine_id);

          //       if($last_plan_referance!=$labelreferences_id){
          //         $cylinder_change_mnt=15;
          //       }else{
          //         $cylinder_change_mnt=0;    
          //       } 

          //       $shiftList = $this->generateShift(null); 
          //       foreach($shiftList as $shoftRow){
          //         $shift=$shoftRow;
          //         $hold_status=$this->getHoldStatus($machine_id, $plandate, $shift);
          //         if($hold_status=="N"){
          //           $available_mnt_shift=$this->getAvailableMinutes($machine_id, $plandate, $shift, $shift_working_minute); 
          //           if($available_mnt_shift!=0){                          
          //               $planedQty = $this->getPlanedQuantity($woArrayList, $base_date);
          //               if($planedQty>=$wototQuantity){
          //                 $planedQty=$wototQuantity;
          //               }

          //               $balancePrintQty=$balanceWoQty-$planedQty;                          
          //               if($balancePrintQty<=0){
          //                 $balancePrintQty=0;
          //               } 
                        
          //               if($balancePrintQty!=0){
          //                 $bal_wo_mnt=$this->getCalculateWoMnt($balancePrintQty, $smv);
          //                 if($produceQty!=0){                                
          //                   $sizeChangesMinute=0;
          //                 }                           

          //                 $print_wo_qty=(int)((($bal_wo_mnt)/$smv)*1000)+1;
          //                 $waistTime=$cylinder_change_mnt+$sizeChangesMinute; 
          //                 $print_wo_mnt=$bal_wo_mnt+$waistTime;

          //                 $print_percentage= (int)(($print_wo_mnt/$available_mnt_shift)*100);

          //                 if($deliverydate<$base_date){
          //                   $plan_type="AL";
          //                 }else{
          //                   $plan_type="A";
          //                 }                            
                          
          //                 if($produceQty!=0){
          //                   $plan_type="APD";
          //                 }

          //                 if($print_percentage<=110){  
          //                   if($print_wo_qty!=0){
          //                     if($print_wo_qty>=$balancePrintQty){
          //                       $print_wo_qty=$balancePrintQty;
          //                     }                                                            
          //                     $this->createPlanRecord($chains_id, $customers_id, $customerbranches_id, $labelreferences_id, $labeltypes_id, $ordertypes_id, $departments_id, $machine_id, $plandate, $print_wo_mnt, $sizeChangesMinute, $cylinder_change_mnt, $print_wo_qty, $shift, $wo_minut, $wototQuantity, $deliverydate, $workorderheaders_id, $plan_type);
          //                   }
          //                 }else{                      
          //                   $print_wo_mnt=$available_mnt_shift;
          //                   $print_wo_qty=(int)((($print_wo_mnt)/$smv)*1000)+1;                               
          //                   if($print_wo_qty>=$balancePrintQty){
          //                     $print_wo_qty=$balancePrintQty;
          //                   }

          //                   if($print_wo_qty!=0){ 
          //                     $this->createPlanRecord($chains_id, $customers_id, $customerbranches_id, $labelreferences_id, $labeltypes_id, $ordertypes_id, $departments_id, $machine_id, $plandate, $print_wo_mnt, $sizeChangesMinute, $cylinder_change_mnt, $print_wo_qty, $shift, $wo_minut, $wototQuantity, $deliverydate, $workorderheaders_id, $plan_type);
          //                   }
          //                 }

          //                 $planed_total=$this->getPlanedQtyNew($base_date, $produceQty, $woArrayList);
          //                 if($wototQuantity<=$planed_total){ 
          //                   $dataList = DB::table('workorderheaders')                       
          //                       ->where('deleted_at','=', null)
          //                       ->where('main_workorder_id','=', $workorderheaders_id)
          //                       ->get();                          
          //                   foreach($dataList as $rowa){
          //                       $wo_update_id = $rowa->id;
          //                       $inputy['is_plan'] = 1;
          //                       $this->updateRecords('workorderheaders',array($wo_update_id),$inputy);   
          //                   }                   
          //                   break 2;
          //                 }
          //               }else{
          //                 break 2;
          //               }
          //           }
          //         }
          //       }             
          //     } 
          //   }            
          //  //dd("kkkkkkkkkk");
          // }else{ 
          //     $dataList = DB::table('workorderheaders')                       
          //         ->where('deleted_at','=', null)
          //         ->where('main_workorder_id','=', $workorderheaders_id)
          //         ->get();  

          //     foreach($dataList as $rowa){
          //         $wo_update_id = $rowa->id;
          //         $error_reasonx = $rowa->error_reason;
          //         if($error_reasonx!='Updated Hold Work Order Chart'){
          //           $inputx['is_plan'] = 1;
          //           $inputx['statuses_id'] = 2;
          //           $inputx['print_statuses_id'] = 2;
          //           $inputx['updated_at'] = date('Y-m-d H:i:s');
          //           $inputx['error_reason'] = "Update Status ID 2 True Planning Process";
          //           $this->updateRecords('workorderheaders',array($wo_update_id),$inputx);   
          //         }                    
          //     } 
          // }
                    
        }          
      }else{
        $next_plan_date = $this->getPlanDate($dep_id);         
        $input_pbt['freeze_status'] = 1;
        $input_pbt['updated_by'] = Auth::user()->id;
        $input_pbt['updated_at'] = date('Y-m-d H:i:s');
        $this->updateRecords('plan_base_tables',array($base_table_id), $input_pbt); 
        $this->insertRecords('plan_base_tables',array('freeze_status'=>0,
                                      'department_id'=>$dep_id,
                                      'company_id'=>Auth::user()->locations->companies->id,
                                      'location_id'=>Auth::user()->locations->id,   
                                      'created_by'=>Auth::user()->id,
                                      'created_at'=>date('Y-m-d H:i:s'),
                                      'date'=>$next_plan_date),false);
      }

      $baseTableData = $this->getPlnBaseTableData($dep_id);
      $searchingVals = ['process_id' => $process_id, 'yes_no' => $yes_no];
      if($baseTableData){
        $base_date=$baseTableData->date;
        $freeze_status=$baseTableData->freeze_status;
        $plan_date = $this->getPlanDate($dep_id);
        $error_des="Notification, Process date :- ".$base_date." ****  Next Process date :- ".$plan_date;

        $processList = $this->genProcessStatus(null);
        $last_planed_date = $this->getLastPlanedDate($dep_id);
        $yesNoList = $this->genYesNo(null);
        $end_time =  date('Y-m-d H:i:s'); 
        $params = [
            'title' => 'Planning Process for Rotary', 
            'last_planed_date' => $last_planed_date,
            'next_plan_date' => $plan_date,
            'error_des' => $error_des,
            'current_freeze_status' => $freeze_status,
            'processList' => $processList,
            'searchingVals' => $searchingVals,
            'yesNoList' => $yesNoList,
            'str_time' => $str_time,
            'end_time' => $end_time,
        ];
        return view('rotary.process.process')->with($params);
      }else{
        $error_des="Please Contact System Administrator";
        dd($error_des);
      }        
    }       
}