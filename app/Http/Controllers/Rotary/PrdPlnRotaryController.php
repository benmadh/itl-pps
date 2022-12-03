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
use Larashop\Models\General\PlnBoard;

Use Larashop\Http\Controllers\General\UtilityHelperGeneral;

use Illuminate\Support\Str;
use Response;
use Auth;
use TPDF;
use Carbon\Carbon;

class PrdPlnRotaryController extends Controller 
{
    use UtilityHelperGeneral;
    
    public function __construct(){
        $this->middleware('permission:production_plan_rotary_access_allow');       
    }

    public function index(){
        $dep_id=12;
        $machines = Machine::where('deleted_at', '=', null)
                            ->where('department_id',"=", $dep_id) 
                            ->where('company_id','=', Auth::user()->locations->companies->id) 
                            ->where('location_id','=', Auth::user()->locations->id)  
                            ->whereIn('machine_category_id',array('1'))                    
                            ->get(); 

        $todate = Carbon::now()->toDateString();
        $constraints = [
            'from' => $todate,
            'to' => $todate,
            'machine_id' => null,                                     
        ];
        $params = [
            'title' => 'Production Plan',
            'machines' => $machines,            
            'searchingVals' => $constraints,
        ];
        return view('rotary.productionplan.productionplan')->with($params);
    }

    public function process(Request $request) {        
        $dep_id=12;
        $this->validate($request, [
            'machine_id' => 'required',
            'from' => 'required|date',
            'to' => 'required|date',
            'machine_operator' => 'required',          
        ]);

        $day_shift_array = [];
        $machine_id=$request['machine_id'];
        $machineDetails= $this->getMachineDetails($machine_id);
        $machine_number=$machineDetails->machin_number;       
        $plandate=$request['from'];
        $to_date=$request['to'];
        $machine_operator=$request['machine_operator'];
        

        TPDF::SetCreator("ITL");
        TPDF::SetAuthor('Dimuth Suranga');
        TPDF::SetTitle('PPS');
        TPDF::SetSubject('TCPDF Tutorial');
        TPDF::SetKeywords('TCPDF, PDF, example, test, guide');        
        //remove default header/footer
        TPDF::setPrintHeader(false);
        //TPDF::setPrintFooter(false);    
        // set default header data
        // TPDF::SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);
        // set header and footer fonts
        // TPDF::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        // TPDF::setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
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

        // // set auto page breaks
        // TPDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // // set font
        TPDF::SetFont('times', '', 8);

        TPDF::AddPage('L', 'A4');       
        // // disable existing columns
        TPDF::resetColumns();

        $html = '<h1>Production Plan - Rotary</h1>';

        $html .= '<h2>Machine : '.$machine_number.'
                    | Operator : '.$machine_operator.'                                          
                </h2>';

        $html .= '<table width="100%" border="1" cellpadding="4">
            <tr>
                <th width="6%" align="center" style="font-weight:bold;" >Plan Date</th> 
                <th width="4%" align="center" style="font-weight:bold;" >Shift</th>
                <th width="10%" align="center" style="font-weight:bold;" > Label Reference</th>   
                <th width="5%" align="center" style="font-weight:bold;" > Length</th>         
                <th width="8%" align="center" style="font-weight:bold;" > Customer </th>         
                <th width="18%" align="center" style="font-weight:bold;" > WO # </th> 
                <th width="5%" align="center" > Order Type</th>     
                <th width="5%"align="center" style="font-weight:bold;" > Qty</th>         
                <th width="5%" align="center" style="font-weight:bold;" > Size Changes</th>
                <th width="5%"align="center" style="font-weight:bold;"> Process Time</th>            
                <th width="7%" align="center" style="font-weight:bold;">Del. Date</th>
                <th width="7%" align="center" style="font-weight:bold;">Current Location</th>           
                <th width="9%" align="center"style="font-weight:bold;" >Special Instructions</th>
            </tr>';
        
        $planData = PlnBoard::where('deleted_at', '=', null)
                                ->where('machine_id','=', $machine_id)
                                ->where('pln_date', '>=', $plandate)
                                ->where('pln_date', '<=', $to_date)
                                ->groupBy('pln_date') 
                                ->groupBy('pln_shift')                                 
                                ->orderBy('pln_date', 'asc') 
                                ->get();

         
        foreach($planData as $planRow){
            $plan_date = $planRow->pln_date;
            $plan_shift = $planRow->pln_shift;
            // if($plan_shift=="D"){
            //     $plan_shif_des="Day";
            // }else{
            //     $plan_shif_des="Night";
            // }            
            if($plan_shift=="D"){
                $plan_shif_des="A";
            }else{
                $plan_shif_des="B";
            }      
            $data = PlnBoard::leftJoin('work_order_headers', 'pln_boards.work_order_header_id', '=', 'work_order_headers.id') 
                                ->where('pln_boards.deleted_at', '=', null)
                                ->where('pln_boards.machine_id','=', $machine_id)
                                ->where('pln_boards.pln_date', '=', $plan_date)
                                ->where('pln_boards.pln_shift', '=', $plan_shift)
                                ->orderBy('work_order_headers.lenght') 
                                ->orderBy('work_order_headers.mwo_delivery_date') 
                                ->get();
            $x=0;    
            $total_qty=0;
            $total_minut=0;
            $total_sizechanges_mnt=0;
            $total_cylinderchange_mnt=0;
            foreach($data as $row){
                $plan_qty = $row->pln_qty;
                $plan_mnt = $row->pln_mnt;
                $deliverydate = Carbon::parse($row->work_order_headers->mwo_delivery_date)->format('d-m-Y');
                $customers_name = $row->work_order_headers->customer_groups->name;              
                $branch_name= $row->work_order_headers->customers->name;
                $labelreferences_name = $row->work_order_headers->label_references->name;               
                $ordertypes_name = $row->work_order_headers->order_types->name;                
                $workorderheaders_id = $row->work_order_header_id;
                $main_workorder_no = $row->work_order_headers->workorder_no;
                $no_of_sizes_after_batch = $row->work_order_headers->no_of_sizes_after_batch;
                $workorder_list= $this->getWoArray($workorderheaders_id, $main_workorder_no);
                $cureent_location= $this->getCurrentLocation($workorderheaders_id);
                $labellenght=  $row->work_order_headers->lenght;
                $woplst = WorkOrderHeader::where('main_workorder_id', '=', $workorderheaders_id)->get();
                $woCnt=$woplst->count();                 
                if($woCnt<=1){
                  $workorder_list="";
                  $workorder_list=$main_workorder_no;  
                }else{
                   $workorder_list='M-'.$main_workorder_no.'**'.$workorder_list;
                }               
                $total_qty+=$plan_qty;
                $total_minut+=$plan_mnt;
                $html .= '<tr>
                            <td align="right">'.($plan_date).'</td> 
                            <td align="center">'.($plan_shif_des).'</td>                                        
                            <td align="left">'.($labelreferences_name).'</td>                                    
                            <td align="right">'.($labellenght).'</td>                          
                            <td align="left">'.(substr($customers_name,0,5)).'</td>
                            <td align="left">'.($workorder_list).'</td> 
                            <td align="left">'.($ordertypes_name).'</td>                                 
                            <td align="right">'.($plan_qty).'</td>                                  
                            <td align="right">'.($no_of_sizes_after_batch).'</td> 
                            <td align="right">'.($plan_mnt).'</td>                                  
                            <td align="center">'.($deliverydate).'</td>
                            <td align="left">'.($cureent_location).'</td> 
                            <td align="right">'.' '.'</td>
                         </tr>';
                $x++; 
            }

            $html .= '<tr>                      
                        <td colspan="7" align="center" style="font-weight:bold;">Total</td>
                        <td align="right" style="font-weight:bold;">'.($total_qty).'</td> 
                        <td align="center" style="font-weight:bold;">&nbsp;</td>                       
                        <td align="right" style="font-weight:bold;">'.($total_minut).'</td>                                   
                        <td align="center" style="font-weight:bold;">&nbsp;</td>
                        <td align="right" style="font-weight:bold;">&nbsp;</td>
                     </tr>';            

        }
        $html .= '</table>';
        TPDF::writeHTML($html, true, false, true, false, 'J');
        TPDF::Output('production_plan.pdf', 'D');
        TPDF::reset();

    }
}