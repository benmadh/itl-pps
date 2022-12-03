<?php

namespace Larashop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Larashop\Models\Planning\Customerbranch;
use Larashop\Models\Planning\Chain_mapheader;
use Larashop\Models\Planning\Workorderline;
use Larashop\Models\General\Employee;
use Illuminate\Support\Facades\DB;

//use Maatwebsite\Excel\Facades\Excel;
use Input;
use Excel;
use Auth;
use Carbon\Carbon;
Use Larashop\Http\Controllers\Planning\UtilityHelperPlanning;

class ImportexportController extends Controller
{
        
    use UtilityHelperPlanning;

        /**

     * Return View file

     *

     * @var array

     */

     public function index(){

        return view('admin.importexport.importexport');
    }

    
    /**
     * File Export Code
     *
     * @var array
     */

    public function downloadExcel(Request $request, $type)

    {
        // $epfno = $request->emp_id;
        // dd($epfno);
        $data = Customerbranch::get()->toArray();
        return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });

        })->download($type);

    }
    /**
     * Import file into database Code
     *
     * @var array
     */

    public function importExcel(Request $request)   {
         
        if($request->hasFile('import_file')){
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) {   

                foreach ($reader->toArray() as $key => $row) {  
                    
                    $referance=$row['field_id']; 
                    //dd($referance);
                    $dataList = DB::table('labelreferences')
                                    ->where('deleted_at', '=', null)
                                    ->where('name', '=', $referance)                         
                                    ->get();
                    
                    foreach($dataList as $dList){
                        $referance_id = $dList->id; 

                        $data1['labelreference_id'] = $referance_id;
                        $data1['machine_id'] = 34;
                        $data1['priority'] = 1;                       
                        $data1['created_by'] = 4;
                        $data1['created_at'] = date('Y-m-d H:i:s'); 

                        if(!empty($data1)) {                     
                           DB::table('machine_labelreferences')->insert($data1);
                        } 
                    
                    }              
                }
                dd("ddd");

                // foreach ($reader->toArray() as $key => $row) {  
                    
                //     $wo_id=$row['main_workorder_no'];
                //     $new_delivery_date=$row['new_delivery_date'];
                    
                //     $dataList = DB::table('workorderheaders')                                   
                //                     ->where('workorderno', '=', $wo_id)
                //                     ->where('deleted_at', '=', null)                         
                //                     ->get();
                //     foreach ($dataList as $dList) {
                //         $workorderheaders_id = $dList->id;
                //         $data['mwo_deliverydate'] = $new_delivery_date;         
                //         $data['pcudate'] = $new_delivery_date;
                //         $data['error_reason'] = "Update new deliver date requested by Isuru on 25-05-2020";  
                //         $data['update_by'] = 4; 
                //         $data['updated_at'] = date('Y-m-d H:i:s');                
                        
                //         if(!empty($data)) { 
                //             $this->updateRecords('workorderheaders',array($workorderheaders_id),$data);
                //         } 
                //     }
                // }   
                // dd("dddd");
                // foreach ($reader->toArray() as $key => $row) {  
                    
                //     $referance=$row['field_id'];                                     
                //     $extra=(int)$row['ups'];
                    
                //     $dataList = DB::table('labelreferences')
                //                     ->where('deleted_at', '=', null)
                //                     ->where('name', '=', $referance)                         
                //                     ->get();
                     
                //     foreach($dataList as $dList){
                //         $referance_id = $dList->id;                       
                //         $data['no_of_ups'] = $extra;         
                //         $data['screen_break_qty'] = 1500;
                //         $data['update_by'] = 4; 
                //         $data['updated_at'] = date('Y-m-d H:i:s');                
                        
                //         if(!empty($data)) { 
                //             $this->updateRecords('labelreferences',array($referance_id),$data);
                //         } 
                    
                //     }              
                // }

                // Update referance details
                // foreach ($reader->toArray() as $key => $row) {
                //     $referance=$row['referance']; 
                //     $smv=$row['smv'];                           
                //     $ups=$row['ups'];
                //     $screen_break_qty=$row['screen_break_qty'];
                //     $pps_chain_id=$row['pps_chain_id'];
                //     $quality_id=$row['quality_id'];
                //     $sl1=$row['sl1'];
                //     $sl2=$row['sl2'];
                //     $dopsing=$row['dopsing'];
                //     $lh=$row['lh'];
                //     $no_colour=1;
                //     $department_id=18;
                //     $label_type=2;

                //     $refList = DB::table('labelreferences')                        
                //             ->where('name', '=', $referance) 
                //             ->where('deleted_at', '=', null)                        
                //             ->first();

                //     if($refList){ 
                //        $ref_id=$refList->id; 

                //         if($sl1){                          
                //            $data1['labelreference_id'] = $ref_id;
                //            $data1['machine_id'] = 25;
                //            $data1['priority'] = $sl1;                       
                //            $data1['created_by'] = 4;
                //            $data1['created_at'] = date('Y-m-d H:i:s'); 

                //            if(!empty($data1)) {                     
                //                DB::table('machine_labelreferences')->insert($data1);
                //            } 
                //         }

                //         if($sl2){                          
                //            $data2['labelreference_id'] = $ref_id;
                //            $data2['machine_id'] = 26;
                //            $data2['priority'] = $sl2;                       
                //            $data2['created_by'] = 4;
                //            $data2['created_at'] = date('Y-m-d H:i:s'); 

                //            if(!empty($data2)) {                     
                //                DB::table('machine_labelreferences')->insert($data2);
                //            } 
                //         }

                //         if($dopsing){                          
                //            $data3['labelreference_id'] = $ref_id;
                //            $data3['machine_id'] = 27;
                //            $data3['priority'] = $dopsing;                       
                //            $data3['created_by'] = 4;
                //            $data3['created_at'] = date('Y-m-d H:i:s'); 

                //            if(!empty($data3)) {                     
                //                DB::table('machine_labelreferences')->insert($data3);
                //            } 
                //         }

                //         if($lh){                          
                //            $data4['labelreference_id'] = $ref_id;
                //            $data4['machine_id'] = 28;
                //            $data4['priority'] = $lh;                       
                //            $data4['created_by'] = 4;
                //            $data4['created_at'] = date('Y-m-d H:i:s'); 

                //            if(!empty($data4)) {                     
                //                DB::table('machine_labelreferences')->insert($data4);
                //            } 
                //         }
                //     }
              //   // }
              // foreach ($reader->toArray() as $key => $row) {  
              //       // dd($reader->toArray()); 
              //       $wo_no=(int)$row['field_id'];  
              //       //$wono=$row['field_id'];                                     
              //       $wono="SW00".$wo_no."W";   
              //      //dd($wono);                 
              //       $dataList = DB::table('workorderheaders')
              //                       ->where('deleted_at', '=', null)
              //                       ->where('main_workorder_no', '=', $wono)                         
              //                       ->get();
                    
              //       $error_reason="Update From Xls ( Isuru )"; 
              //       $freeze_status="Y";
              //       $is_plan=1;  
              //       $print_statuses_id=2;
              //       $statuses_id=2;
              //       //$mrn_print_status="Y";

              //       foreach($dataList as $dList){
              //           $workorderheaders_id = $dList->id;                      
              //           //$data['mrn_print_status'] = $mrn_print_status; 
              //           $data['is_plan'] = $is_plan;
              //           $data['freeze_status'] = $freeze_status;
              //           $data['error_reason'] = $error_reason;
              //           $data['statuses_id'] = $statuses_id; 
              //           $data['print_statuses_id'] = $print_statuses_id;                       
              //           $data['update_by'] = 4;
              //           $data['updated_at'] = date('Y-m-d H:i:s');                
                        
              //           if(!empty($data)) { 
              //               $this->updateRecords('workorderheaders',array($workorderheaders_id),$data);
              //           } 
                    
              //       }              
              //   }

                // foreach ($reader->toArray() as $key => $row) {  
                //     // dd($reader->toArray()); 
                //     //$wo_no=(int)$row['field_id'];  
                //     $reference=$row['field_id'];
                //     $smv=$row['smv'];
                //     $smv=$row['ups'];                                      
                //    // dd($smv);
                //     $data = DB::table('labelreferences')                        
                //         ->where('name', '=', $reference) 
                //         ->where('deleted_at', '=', null)                        
                //         ->first();

                //     if($data){
                //         $labelreferences_id= $data->id;
                        
                //         if($smv!=null){
                //             $smvList = DB::table('smvs')                        
                //                 ->where('labelreference_id', '=', $labelreferences_id) 
                //                 ->where('deleted_at', '=', null)                        
                //                 ->first();

                //             if($smvList){                            
                //                 $smv_id=$smvList->id;                           
                //                 $data_03['smv'] = $smv;                
                //                 $data_03['update_by'] = 4;
                //                 $data_03['updated_at'] = date('Y-m-d H:i:s');  
                //                 if(!empty($data_03)) { 
                //                      $this->updateRecords('smvs',array($smv_id),$data_03);
                //                 }
                //             }else{                                                       
                //                 //dd("dddd");
                //                 $data_01['labelreference_id'] = $labelreferences_id;
                //                 $data_01['unit_id'] = 1;
                //                 $data_01['length'] = 1;
                //                 $data_01['speed_factor'] = 1;
                //                 $data_01['critical_factor'] = 1;
                //                 $data_01['smv'] = $smv;
                //                 $data_01['number_of_pcs'] = 1000; 
                //                 $data_01['created_by'] = 4;
                //                 $data_01['created_at'] = date('Y-m-d H:i:s');

                //                 if(!empty($data_01)) {                     
                //                     DB::table('smvs')->insert($data_01);  
                //                 }

                //             }

                //         }
                //     }
                // }    

                dd("Done");

              // Update payroll details
                
                // foreach ($reader->toArray() as $key => $row) {
                //     $epf_no = $row['epf_no'];

                //     $name = $row['name'];
                //     $call_name = $row['call_name'];                   
                //     $gender = $row['gender'];
                //     $department_id = $row['department_id'];
                //     $designation_id = $row['designation_id'];
                //     $shift = $row['shift'];

                //     $empList = DB::table('employees')                        
                //             ->where('epfno', '=', $epf_no) 
                //             ->where('deleted_at', '=', null)                        
                //             ->first();

                //     // dd($empList);

                //     if($empList){ 
                //         $emp_id=$empList->id;
                //         $data_03['name'] = $name;
                //         $data_03['callname'] = $call_name;                   
                //         $data_03['gender'] = $gender;
                //         $data_03['departmentid'] = $department_id;
                //         $data_03['designationid'] = $designation_id;
                //         $data_03['shift'] = $shift;
                //         $data_03['activation'] = 1;
                //         $data_03['created_by'] = 4;
                //         $data_03['created_at'] = date('Y-m-d H:i:s'); 
                //         if(!empty($data_03)) { 
                //              $this->updateRecords('employees',array($emp_id),$data_03);
                //         }   

                //     }else{
                //         $data['epfno'] = $epf_no;
                //         $data['name'] = $name;
                //         $data['callname'] = $call_name;                   
                //         $data['gender'] = $gender;
                //         $data['departmentid'] = $department_id;
                //         $data['designationid'] = $designation_id;
                //         $data['shift'] = $shift;
                //         $data['activation'] = 1;
                //         $data['created_by'] = 4;
                //         $data['created_at'] = date('Y-m-d H:i:s');    
                //         if(!empty($data)) {                     
                //             DB::table('employees')->insert($data);  
                //          }
                //     }
                // }
            });
           
            return back()->with('success','Insert Record successfully.');
        }
        //return back()->with('error','Please Check your file, Something is wrong there.');
    }
}
