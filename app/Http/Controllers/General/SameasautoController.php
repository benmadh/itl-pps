<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;


use Larashop\Models\General\Department;
use Larashop\Models\General\Status;
Use Larashop\Http\Controllers\General\UtilityHelperGeneral;
use Larashop\Models\General\WorkOrderHeader;
use Larashop\Models\General\WorkOrderLine;
use Larashop\Models\General\OrderTracking;

use Response;
use Auth;
use TPDF;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Use Larashop\Http\Controllers\Planning\UtilityHelperPlanning;

class SameasautoController extends Controller
{
    use UtilityHelperGeneral;

    // public function __construct(){
    //     $this->middleware('permission:sameas_auto_access_allow');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){       
        $workorders =  null; 
        $searchingVals = null; 

        $params = [
            'title' => 'Automation of Same as Details',
            'searchingVals' => $searchingVals,  
            'workorders' => $workorders,          
        ];

        return view('general.sameasauto.sameasauto_list')->with($params);
    }

    public function edit_sameas_auto($id){   
      
        try{
            $workorderheaders = WorkOrderHeader::findOrFail($id); 
            //dd($workorderheaders);
            $woh_id=$workorderheaders->id; 
            $departments_id=$workorderheaders->department_id;
            $chain_id=$workorderheaders->chain_id;
            $sameData = DB::table('work_order_headers') 
                        ->select(DB::raw('count(id) as sameCount'))       
                        ->where('deleted_at', '=', null)
                        ->where('main_workorder_id', '=', $id)
                        ->first();
            $same_count=0;            
            if($sameData){
                $same_count=$sameData->sameCount;
            }
            
            if($same_count>1){ 
                $same_as_status="Existing Same as List";
                $colour_code="#02075d";               
                $sameAsWoList = WorkOrderHeader::where('deleted_at', '=', null)
                     ->where('main_workorder_id', '=', $id)
                     //->where('freeze_status', '=','N')
                     ->orderBy('customer_order_no') 
                     ->get();

                $maxSizeChanWoId=$id;
            }else{  
                $same_as_status="New Same as List";  
                $colour_code="#fdfe03";           
                $samas_felds=$workorderheaders->same_as_felds;
                //dd($samas_felds);
                if($chain_id==22){
                  if($samas_felds!=null || $samas_felds!=""){
                      $lineData = WorkOrderLine::where('deleted_at', '=', null)
                                       ->where('work_order_header_id', '=', $woh_id)
                                       ->orderBy('id') 
                                       ->get();


                      $ide_list = array();
                      $ide_array = [];   
                      foreach ($lineData as $lineRow) {
                          $line_woh_id=$lineRow->work_order_header_id;
                          $line_woh_no=$lineRow->workorder_no;
                          $barcode=$lineRow->barcode;
                          $article=$lineRow->article;                          

                          $lineData2 = WorkOrderLine::where('deleted_at', '=', null)
                                       ->where('barcode', '=', $barcode)
                                       ->where('article', '=', $article)     
                                       ->get();

                          foreach ($lineData2 as $lineRow2) {
                              $line_woh_id2=$lineRow2->work_order_header_id;

                              if (!array_key_exists($line_woh_id2, $ide_array)){ 
                                  $ide_array[$line_woh_id2]=0;  
                                  array_push($ide_list, $line_woh_id2);
                              }
                          }
                      }

                  }

                  $sameAsWoList = WorkOrderHeader::where('deleted_at', '=', null)
                       ->whereIn('id',$ide_list)
                       ->where('freeze_status', '=','N')
                       ->where('same_as_status', '=','N')
                       ->where('mrn_print_status', '=','N')
                       ->where('statuses_id', '=', 1)
                       ->where('print_statuses_id', '=', 1)
                       ->where('packing_statuses_id', '=', 1)
                       ->where('despatch_statuses_id', '=', 1)                      
                       ->where('samas_felds', '=', $samas_felds)                       
                       ->orderBy('customer_order_no') 
                       ->get(); 

                  $maxSizeChanWoId = WorkOrderHeader::where('deleted_at', '=', null)
                       ->whereIn('id',$ide_list)
                       ->where('freeze_status', '=','N')
                       ->where('same_as_status', '=','N')
                       ->where('mrn_print_status', '=','N')
                       ->where('statuses_id', '=', 1)
                       ->where('print_statuses_id', '=', 1)
                       ->where('packing_statuses_id', '=', 1)
                       ->where('despatch_statuses_id', '=', 1) 
                       ->where('samas_felds', '=', $samas_felds)                       
                       ->orderBy('sizechanges', 'desc') 
                       ->value('id'); 

                }else{
                  if($samas_felds!=null || $samas_felds!=""){

                    if($departments_id==33){
                      $sameAsWoList = WorkOrderHeader::where('deleted_at', '=', null)
                                                       //->where('freeze_status', '=','N')
                                                       ->where('same_as_status', '=','N')
                                                       //->where('mrn_print_status', '=','N')
                                                       ->where('status_id', '=', 1)
                                                       ->where('print_status_id', '=', 1)
                                                       ->where('packing_status_id', '=', 1)
                                                       ->where('despatch_status_id', '=', 1) 
                                                       ->where('same_as_felds', '=', $samas_felds)
                                                       ->orderBy('customer_order_no') 
                                                       ->get();

                       $maxSizeChanWoId = WorkOrderHeader::where('deleted_at', '=', null)
                                                       //->where('freeze_status', '=','N')
                                                       ->where('same_as_status', '=','N')
                                                       //->where('mrn_print_status', '=','N')
                                                       ->where('status_id', '=', 1)
                                                       ->where('print_status_id', '=', 1)
                                                       ->where('packing_status_id', '=', 1)
                                                       ->where('despatch_status_id', '=', 1) 
                                                       ->where('same_as_felds', '=', $samas_felds)
                                                       ->orderBy('size_changes', 'desc') 
                                                       ->value('id');
                    }else{
                         $sameAsWoList = WorkOrderHeader::where('deleted_at', '=', null)
                                                       //->where('freeze_status', '=','N')
                                                       ->where('same_as_status', '=','N')
                                                       ->where('mrn_print_status', '=','N')
                                                       ->where('status_id', '=', 1)
                                                       ->where('print_status_id', '=', 1)
                                                       ->where('packing_status_id', '=', 1)
                                                       ->where('despatch_status_id', '=', 1) 
                                                       ->where('same_as_felds', '=', $samas_felds)
                                                       ->orderBy('customer_order_no') 
                                                       ->get();

                        $maxSizeChanWoId = WorkOrderHeader::where('deleted_at', '=', null)
                                                       //->where('freeze_status', '=','N')
                                                       ->where('same_as_status', '=','N')
                                                       ->where('mrn_print_status', '=','N')
                                                       ->where('status_id', '=', 1)
                                                       ->where('print_status_id', '=', 1)
                                                       ->where('packing_status_id', '=', 1)
                                                       ->where('despatch_status_id', '=', 1) 
                                                       ->where('same_as_felds', '=', $samas_felds)
                                                       ->orderBy('size_changes', 'desc') 
                                                       ->value('id');

                    }

                  }else{
                      $sameAsWoList = WorkOrderHeader::where('deleted_at', '=', null)
                       ->where('main_workorder_id', '=', $id)                      
                       ->orderBy('customer_order_no') 
                       ->get();

                      $maxSizeChanWoId=$id;
                  }
                }                
            }

            $wotList = ['Normal','Main'];            
            if($maxSizeChanWoId){
                $woMaxId = WorkOrderHeader::findOrFail($maxSizeChanWoId); 
            }else{
                $woMaxId=null;
            }
               
            //dd($woMaxId);                                
            $params = [
                'title' => 'Allocate work orders in to main order',
                'workorderheaders' => $woMaxId,              
                'sameAsWoList' => $sameAsWoList, 
                'same_as_status' => $same_as_status,
                'colour_code' => $colour_code, 
                'wotList' => $wotList,  
                'maxSizeChanWoId' => $maxSizeChanWoId,     
            ];

            return view('general.sameasauto.sameasauto_edit')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function update_sameas_auto(Request $request) { 

            foreach ($request->workorderheaders_id as $key => $v) {              
              $woType=$request->woType[$key];
              if($woType==1){
                $id=$request->workorderheaders_id[$key];
              }
            }
            //dd($id);        
            //$id = $request->id;            
            $worList=$this->getWoNo($id);
            $workorders_no= $worList->workorder_no; 
            $deliverydate= $worList->delivery_date; 

            $woType= $request->woType;   
            //dd($woType);      
            $constraints = [                        
                'workorderno' => $workorders_no,            
            ];            

            foreach ($request->workorderheaders_id as $key => $v) { 
                $data = array('main_workorder_id'=> $id,
                                'mwo_delivery_date'=> $deliverydate,
                                'same_as_status'=> "Y",
                                'updated_by'=> Auth::user()->id,
                                'updated_at'=> date('Y-m-d H:i:s'),
                                'main_workorder_no'=> $workorders_no);

                $did = $request->workorderheaders_id[$key];
                WorkOrderHeader::where('id', $did)
                        ->update($data);
            } 

            $exsit_wo_list = WorkOrderHeader::where('deleted_at', '=', null)
                     ->where('main_workorder_id','=',$id)                     
                     ->get();
            
            foreach ($exsit_wo_list as $row => $v2) {
                $main_workorder_id = $v2->id;
                $delete = true;                
                foreach ($request->wo_id as $key => $v) {
                  $did = $request->wo_id[$key];
                  if ($did==$main_workorder_id){
                    $delete = false;
                  } 
                }

                if($delete){ 
                    $worListX=$this->getWoNoX($main_workorder_id);
                    $work_orders_no= $worListX->workorder_no; 
                    $deliverydate= $worListX->delivery_date;

                    $data = array('main_workorder_id'=> $main_workorder_id,
                                    'updated_by'=> Auth::user()->id,
                                    'updated_at'=> date('Y-m-d H:i:s'),
                                    'mwo_delivery_date'=> $deliverydate,
                                    'same_as_status'=> "N",
                                    'main_workorder_no'=> $work_orders_no);
                    WorkOrderHeader::where('id', $main_workorder_id)->update($data); 
                }
            }


            $updDeliveryDate = DB::table('work_order_headers')
                        ->select('pcu_date')   
                        ->where('main_workorder_id','=',$id) 
                        ->where('deleted_at', '=', null)
                        ->orderBy('pcu_date','asc')                    
                        ->first();
                      
                       
            if($updDeliveryDate->pcu_date){
                $upd_pcudate= $updDeliveryDate->pcu_date;
                $data11['mwo_delivery_date'] = $upd_pcudate; 
                $data11['updated_by'] = Auth::user()->id;
                $data11['updated_at'] = date('Y-m-d H:i:s'); 
                $this->updateRecordsMainDeliveryDate('work_order_headers',array($id),$data11);
            }

            //dd($upd_pcudate);
            $query = DB::table('work_order_headers')
                          ->leftJoin('customers', 'work_order_headers.customer_id', '=', 'customers.id')
                          ->leftJoin('departments', 'work_order_headers.department_id', '=', 'departments.id')
                          ->leftJoin('chains', 'work_order_headers.chain_id', '=', 'chains.id')
                          ->leftJoin('order_types', 'work_order_headers.order_type_id', '=', 'order_types.id')
                          ->select('work_order_headers.*', 'customers.name as cus_name','departments.name as dep_name','chains.name as chain_name', 'order_types.name as ordertype_name', 'order_types.color_picker_id as colorpicker_id')
                          ->where('work_order_headers.deleted_at', '=', null) 
                          ->where('main_workorder_no', 'like', '%'.$workorders_no.'%')
                          ->groupBy('main_workorder_no')    
                          ->get();

            $data_array = []; 
            $data_array[0]["errorsMsg"]="N";
            $data_array[0]["rcStatus"]="Changes to have been successfully saved. ( ".$workorders_no." )";
            return view('general/sameasauto/sameasauto_list', ['workorders' => $query,
                                                            'data_array' => $data_array,
                                                            'searchingVals' => $constraints]);
    }

    public function print_sameas_auto($id){
        $mwoList=$this->getWoNo($id);
        $main_wo_no= $mwoList->workorder_no;
        $customer_order_no= $mwoList->customer_order_no;    
              
        $bgcolor="#C9C9AE";
        $bgcolor_line_1="#f2f2eb";
        $bgcolor_tot="#F4F4EF";
        $main_wo_array_list=[];        
        $main_wo_array_list[$id]=$main_wo_no;
        $wohLst = WorkOrderHeader::where('main_workorder_id', '=', $id)->get();
        $line_no=1;
        foreach($wohLst  as $wohRow) {
          $header_id=$wohRow->id;
          $workorderno=$wohRow->workorder_no;
          if($main_wo_no!=$workorderno){
            $main_wo_array_list[$header_id]=$workorderno;
            $line_no++;
          }
        }

        $wo_array_list = [];
        $wo_array_qty_list = [];
        $art_work_status = [];
        $size_array = [];        
        $size_array_x = [];
        $refer_size_array = [];   
        $main_wo_size_array = array();    
        $main_wo_size_array_x=[]; 
        $size_qty_array=[];
        $size_array_des=[];
        $size_array_list = array();
        $no_of_size=1;
        $same_as="N";
        $j=0;  
             
        foreach($main_wo_array_list as $key7 => $val7 ) {
            $header_id=$key7;
            $workorderno=$val7;
            $wo_array_list[$j]=$workorderno;
            if($j==0){
              $art_work_status[$j]="Main Order";
            }else{
              $art_work_status[$j]="N/A";
            }
            
            $wlLst = WorkOrderLine::where('deleted_at', '=', null)                       
                    ->where('work_order_header_id',$header_id)
                    ->get();
            $k=0;
            $line_qty_tot=0;
            foreach($wlLst  as $wlRow) {
              $size_no=$wlRow->size;
              $size_qty=$wlRow->quantity;
              $size_qty_array[$j][$workorderno][$size_no]=$size_qty;
              $size_array_des[$j][$workorderno][$k]=$size_no;
              $line_qty_tot+=$size_qty;

              if (!array_key_exists($size_no, $size_array)){
                $size_array[$size_no]=0;                               
                array_push($size_array_list, $size_no);
              }

              if($j==0){
                array_push($main_wo_size_array, $size_no);  
                $main_wo_size_array_x[$size_no]=0;              
              }
              $k++;
            } 
            $wo_array_qty_list[$j]=$line_qty_tot;
            $j++;               
        }
        //dd($size_array);
        foreach ($size_array as $key1 => $value1) {
          if (!array_key_exists($key1, $main_wo_size_array_x)){
            $refer_size_array[$key1]=0;
          }
        } 
        //dd($refer_size_array);
        $mt_size_array_des=[];
        for ($i=0; $i < count($wo_array_list); $i++) { 
          $wono=$wo_array_list[$i];            
          for ($l=0; $l < count($size_array_list); $l++) {                
              $mt_size_array_des[$i][$wono][$l]="";
          }
        }
       
        for ($i=0; $i < count($wo_array_list); $i++) { 
            $wono=$wo_array_list[$i];
            foreach ($size_array_des[$i][$wono] as $key => $value) {
              $size_key=$key;
              $size_value=$value;
              // echo $wono.' - '.$key.' - '.$value;
              // echo "</br>";
              for ($k=0; $k < count($size_array_list); $k++) {  
                $array_size=$size_array_list[$k]; 
                if($array_size==$size_value){
                   break;
                }
              }
              $mt_size_array_des[$i][$wono][$k]=$size_value;
            }
        }

        $miss_size_array=[];

        for ($m=0; $m < count($wo_array_list); $m++) { 
          $wono=$wo_array_list[$m];
          $art_atatus="N";
          foreach ($size_array_des[$m][$wono] as $key => $value) {
            $size_key=$key;
            $size_value=$value;

            if(!in_array($size_value , $main_wo_size_array)){               
                $art_atatus="Y";
                $miss_size_array[$wono]=$size_value;
                break;
            }                          
          }           
          if($art_atatus=="Y"){
            $art_work_status[$m]="Refer";
          }            
        }  
       
        $diff_size_array=[];
        $size_array_list_01 = array();
        $array_cnt_main = count($main_wo_size_array_x);
        $jk=0;        
        for ($d=0; $d < count($wo_array_list); $d++) { 
          $wo_no=$wo_array_list[$d];
          if($d!=0){
            $h=0;
            foreach ($mt_size_array_des[$d][$wo_no] as $key => $value) {
              if($key>=$array_cnt_main){
                $size_value=$value;
                if($value){
                  $diff_size_array[$jk][$wo_no][$h]=$value;                
                  array_push($size_array_list_01, $value);
                  $h++;
                }                             
              }             
            }            
            $jk++;
          }
        }
        // $hold=[];
        // foreach($diff_size_array as $k3 => $v3){
        //   dd($v3);
        //   if(!( isset ($hold[$v3])))
        //       $hold[$v3]=1;
        //   else
        //       unset($diff_size_array[$k3]);
        // }
        // dd($diff_size_array);
      // $temp=array_unique($diff_size_array);
       // dd($size_array_list_01);    
// $temp = array_values( array_flip( array_flip( $diff_size_array ) ) );
//         dd($temp);      
        $no_of_size=count($size_array);
        $colspan_cnt=count($size_array)+2;  
        TPDF::SetCreator("ITL");
        TPDF::SetAuthor('Nicola Asuni');
        TPDF::SetTitle('PPS');
        TPDF::SetSubject('TCPDF Tutorial');
        TPDF::SetKeywords('TCPDF, PDF, example, test, guide');
        TPDF::setPrintHeader(false);
        TPDF::setPrintFooter(true);
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

        // // set font
        TPDF::SetFont('times', '', 8);

        if($no_of_size>6){
          TPDF::AddPage('L', 'A4');
        }else{
          TPDF::AddPage('P', 'A4');
        }
               
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

        $html .= '<table class="tbl3" width="100%" cellpadding="2">';
        $html .= '<tr>            
                    <td align="left" ><h1>Size Breakdown List for Same as Orders</h1> </td>
                    <td align="right" ><h1>Main WO # '.$main_wo_no.'</h1> </td>
                  </tr>';
        $html .= '<tr> 
                    <td align="left" > </td>
                    <td align="right" ><h1>Customer Order # '.$customer_order_no.'</h1> </td>
                  </tr>';
        $html .= '</table>'; 
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<table width="100%" border="1" cellpadding="4">
              <tr>
                  <th width="5%" align="center" bgcolor="'.($bgcolor).'">RNO </th> 
                  <th width="12%" align="left" bgcolor="'.($bgcolor).'">WO #</th> 
                  <th width="63%" colspan="'.$no_of_size.'" align="left" bgcolor="'.($bgcolor).'">Size Breakdown</th> 
                  <th width="10%" align="center" bgcolor="'.($bgcolor).'">WO Qty</th>
                  <th width="10%" align="center" bgcolor="'.($bgcolor).'">Artwork Status</th>
              </tr>';  
        $tot_wo_qty=0;
        for ($n=0; $n < count($wo_array_list); $n++) { 
          $wo_no=$wo_array_list[$n];
          $wo_qty_tot=$wo_array_qty_list[$n];

          $tot_wo_qty+=$wo_qty_tot;
         // dd();
          if($n==0){
            $html .= '<tr>
                        <td align="center" bgcolor="'.($bgcolor_line_1).'" style="font-weight:bold;">'.($n).'</td>
                        <td align="center" bgcolor="'.($bgcolor_line_1).'" style="font-weight:bold;">'.($wo_no).'</td>';
                        foreach ($mt_size_array_des[$n][$wo_no] as $key => $value) {
                          $size_value=$value;
                          $html .= '<td align="center" bgcolor="'.($bgcolor_line_1).'" style="font-weight:bold;">'.($value).'</td>';
                        } 
                        $html .= '<td align="right" bgcolor="'.($bgcolor_line_1).'" style="font-weight:bold;">'.($wo_array_qty_list[$n]).'</td>';
                        $html .= '<td align="left" bgcolor="'.($bgcolor_line_1).'" style="font-weight:bold;">'.($art_work_status[$n]).'</td>';
            $html .= '</tr>';
          }else{
            $html .= '<tr>
                        <td align="center">'.($n).'</td>
                        <td align="center">'.($wo_no).'</td>';
                        foreach ($mt_size_array_des[$n][$wo_no] as $key => $value) {
                          $size_value=$value;
                          $html .= '<td align="center">'.($value).'</td>';
                        } 
                        $html .= '<td align="right">'.($wo_array_qty_list[$n]).'</td>';
                        $html .= '<td align="left">'.($art_work_status[$n]).'</td>';
            $html .= '</tr>'; 

          }
          
        }
        $html .= '<tr>                    
                    <td colspan="'.$colspan_cnt.'" align="center" bgcolor="'.($bgcolor_tot).'" style="font-weight:bold;">Total</td>                                            
                    <td bgcolor="'.($bgcolor_tot).'" align="right" style="font-weight:bold;">'.($tot_wo_qty).'</td> 
                    <td align="center" bgcolor="'.($bgcolor_tot).'" style="font-weight:bold;"></td> 
                   </tr>';        
        $html .= '</table>'; 

        $html .= '<br>';
        $html .= '<br>';
       
        if($refer_size_array){
           $html .= '<table width="100%" border="1" cellpadding="4">
                  <tr>                   
                      <th colspan="'.$no_of_size.'" align="left" bgcolor="'.($bgcolor).'">Additional Sizes Need To Be Print</th>
                  </tr>';
          $html .= '<tr>';                      
                  foreach ($refer_size_array as $key => $value) {
                    $html .= '<td align="center" bgcolor="'.($bgcolor_line_1).'" style="font-weight:bold;">'.($key).'</td>';
                  }                   
          $html .= '</tr>';
          $html .= '</table>';          
        }       
       
        TPDF::writeHTML($html, true, false, true, false, 'J');
        // TPDF::AddPage();
        // TPDF::setPage(TPDF::getPage());
        TPDF::Output('Same_as_List-'.$main_wo_no, 'D');
        TPDF::reset();
    }

    public function search(Request $request){

        $this->validate($request, [
            'workorderno' => 'required',
        ]);

        $constraints = [                        
            'workorderno' => $request['workorderno'],            
        ];

        $main_workorders=$request['workorderno'];

        $query = DB::table('work_order_headers')
                  ->leftJoin('customers', 'work_order_headers.customer_id', '=', 'customers.id')
                  ->leftJoin('departments', 'work_order_headers.department_id', '=', 'departments.id')
                   ->leftJoin('chains', 'work_order_headers.chain_id', '=', 'chains.id')
                  ->leftJoin('order_types', 'work_order_headers.order_type_id', '=', 'order_types.id')                          
                  ->select('work_order_headers.*', 'customers.name as cus_name','departments.name as dep_name','chains.name as chain_name', 'order_types.name as ordertype_name', 'order_types.color_picker_id as colorpicker_id')
                  ->where('work_order_headers.deleted_at', '=', null) 
                  ->where('main_workorder_no', 'like', '%'.$main_workorders.'%')
                  ->groupBy('main_workorder_no')    
                  ->get();
      
       $data_array = [];  
       if($query->isEmpty()){
          $dataWo = DB::table('work_order_headers')                  
                ->where('workorder_no', 'like', '%'.$main_workorders.'%') 
                ->where('deleted_at', '=', null)  
                ->orderBy('id', 'DESC')                  
                ->first();
          $mainWoNo=null;
          if($dataWo){
             $mainWoNo=$dataWo->main_workorder_no;
             $data_array[0]["rcStatus"]=$main_workorders." already same as in ".$mainWoNo;
             $data_array[0]["errorsMsg"]="Y";
          }else{
              $data_array[0]["rcStatus"]=$main_workorders." not found... ";
              $data_array[0]["errorsMsg"]="Y";
          }            
       }       
       //dd($query);
        return view('general/sameasauto/sameasauto_list', ['workorders' => $query,
                                                            'data_array' => $data_array,
                                                            'searchingVals' => $constraints]);
    }

}