<?php

namespace Larashop\Http\Controllers\Rotary;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Charts;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
Use Larashop\Http\Controllers\General\UtilityHelperGeneral;

class DbRotaryController extends Controller
{
    
    use UtilityHelperGeneral;
    public function index()
    {
        $departments_id=12;
        $fromdate = Carbon::now()->startOfMonth()->toDateString();
        $todate = Carbon::now()->toDateString(); 
        $end = Carbon::parse($todate);
        $now = Carbon::parse($fromdate);
        $date_diff = $end->diffInDays($now);
        $table_type="Region";
        $oee_region=$this->getOeeEfficiency($table_type, $date_diff+1, $now, $departments_id);
        $eff_trg_array=$this->getEffTrgetArray($date_diff+1, $now);
        $date_arre=$this->getDateArray($date_diff+1, $now);       
        $table_type="Benchmark";
        $oee_benchmark=$this->getOeeEfficiency($table_type, $date_diff+1, $now , $departments_id);   
        $table_type="Region";
        $quality_region=$this->getQualityEfficiency($table_type, $date_diff+1, $now, $departments_id);
        $table_type="Benchmark";
        $quality_benchmark=$this->getQualityEfficiency($table_type, $date_diff+1, $now, $departments_id);
        
        $prd_qty_arr=$this->getProductionQty($end, $now, $date_diff+1, $departments_id);
        $cut_qty_arr=$this->getCuttingQty($end, $now, $date_diff+1, $departments_id);

        $oee_chart = Charts::multi('line', 'highcharts')
                ->title('OEE (%)')
                ->elementLabel('%')
                ->colors(['#ffe6e6', '#41bbf4','#89f442'])
                ->labels($date_arre)
                ->dataset('Max %', $eff_trg_array)
                ->dataset('Region', $oee_region)
                ->dataset('Benchmark', $oee_benchmark);

        $quality_chart = Charts::multi('line', 'highcharts')
                ->title('Quality (%)')
                ->elementLabel('%')
                ->colors(['#ffe6e6', '#41bbf4','#89f442'])
                ->labels($date_arre)
                ->dataset('Max %', $eff_trg_array)
                ->dataset('Region', $quality_region)
                ->dataset('Benchmark', $quality_benchmark);


        $chart = Charts::multi('line', 'highcharts')
                ->title('Daily Basis Figures')
                ->elementLabel('PCS')
                ->colors(['#008000', '#0000ff','#FFFF00','#800080','#FF00FF'])
                ->labels($date_arre)
                ->dataset('Printing', $prd_qty_arr)
                ->dataset('Cutting', $cut_qty_arr);

        $dataObjPrinting = DB::table('printings') 
                                ->select(DB::raw('sum(quantity) as qty'),DB::raw('YEAR(date) year, MONTHNAME(date) month'))
                                ->where('date','>=',$fromdate)
                                ->where('date','<=',$todate)
                                ->where('department_id','=',$departments_id)
                                ->where('deleted_at','=', null) 
                                ->groupBy('year','month')
                                ->get();

        $dataObjCutting = DB::table('cuttings') 
                                ->select(DB::raw('sum(quantity) as qty'),DB::raw('YEAR(date) year, MONTHNAME(date) month'))
                                ->where('date','>=',$fromdate)
                                ->where('date','<=',$todate)
                                ->where('department_id','=',$departments_id)
                                ->where('deleted_at','=', null) 
                                ->groupBy('year','month')
                                ->get();

        $chart2 = Charts::multi('bar', 'highcharts')
                            ->title('Monthly Basis Figures - Cumulative')
                            ->elementLabel('PCS')
                            ->colors(['#008000', '#0000ff','#FFFF00','#800080','#FF00FF'])
                            ->labels($dataObjPrinting->pluck('month')) 
                            ->dataset('Printing', $dataObjPrinting->pluck('qty'))
                            ->dataset('Cutting', $dataObjCutting->pluck('qty'));

        $params = [            
            'fromdate' =>  $fromdate,
            'todate' =>  $todate, 
            'oee_chart' => $oee_chart, 
            'quality_chart' => $quality_chart,
            'chart' => $chart, 
            'chart2' => $chart2,  
        ];
        
        return view('rotary.dashboard.dashboard')->with($params);
    }

    public function chartDetails(Request $request)
    {

        $this->validate($request, [
            'fromdate' => 'required',
            'todate' => 'required',
        ]);

        $fromdate = $request->input('fromdate');
        $todate = $request->input('todate');
        $departments_id=12;
        $end = Carbon::parse($todate);
        $now = Carbon::parse($fromdate);
        $date_diff = $end->diffInDays($now);
        $table_type="Region";
        $oee_region=$this->getOeeEfficiency($table_type, $date_diff+1, $now, $departments_id);
        $eff_trg_array=$this->getEffTrgetArray($date_diff+1, $now);
        $date_arre=$this->getDateArray($date_diff+1, $now);       
        $table_type="Benchmark";
        $oee_benchmark=$this->getOeeEfficiency($table_type, $date_diff+1, $now, $departments_id);   
        $table_type="Region";
        $quality_region=$this->getQualityEfficiency($table_type, $date_diff+1, $now, $departments_id);
        $table_type="Benchmark";
        $quality_benchmark=$this->getQualityEfficiency($table_type, $date_diff+1, $now, $departments_id);
        $prd_qty_arr=$this->getProductionQty($end, $now, $date_diff+1, $departments_id);
        $cut_qty_arr=$this->getCuttingQty($end, $now, $date_diff+1, $departments_id);

        $oee_chart = Charts::multi('line', 'highcharts')
                ->title('OEE (%)')
                ->elementLabel('%')
                ->colors(['#ffe6e6', '#41bbf4','#89f442'])
                ->labels($date_arre)
                ->dataset('Max %', $eff_trg_array)
                ->dataset('Region', $oee_region)
                ->dataset('Benchmark', $oee_benchmark);

        $quality_chart = Charts::multi('line', 'highcharts')
                ->title('Quality (%)')
                ->elementLabel('%')
                ->colors(['#ffe6e6', '#41bbf4','#89f442'])
                ->labels($date_arre)
                ->dataset('Max %', $eff_trg_array)
                ->dataset('Region', $quality_region)
                ->dataset('Benchmark', $quality_benchmark);

        $chart = Charts::multi('line', 'highcharts')
                ->title('Daily Basis Figures')
                ->elementLabel('PCS')
                ->colors(['#008000', '#0000ff','#FFFF00','#800080','#FF00FF'])
                ->labels($date_arre)
                ->dataset('Printing', $prd_qty_arr)
                ->dataset('Cutting', $cut_qty_arr);

        $dataObjPrinting = DB::table('printings') 
                                ->select(DB::raw('sum(quantity) as qty'),DB::raw('YEAR(date) year, MONTHNAME(date) month'))
                                ->where('date','>=',$fromdate)
                                ->where('date','<=',$todate)
                                ->where('department_id','=',$departments_id)
                                ->where('deleted_at','=', null) 
                                ->groupBy('year','month')
                                ->get();

        $dataObjCutting = DB::table('cuttings') 
                                ->select(DB::raw('sum(quantity) as qty'),DB::raw('YEAR(date) year, MONTHNAME(date) month'))
                                ->where('date','>=',$fromdate)
                                ->where('date','<=',$todate)
                                ->where('department_id','=',$departments_id)
                                ->where('deleted_at','=', null) 
                                ->groupBy('year','month')
                                ->get();

        $chart2 = Charts::multi('bar', 'highcharts')
                            ->title('Monthly Basis Figures - Cumulative')
                            ->elementLabel('PCS')
                            ->colors(['#008000', '#0000ff','#FFFF00','#800080','#FF00FF'])
                            ->labels($dataObjPrinting->pluck('month')) 
                            ->dataset('Printing', $dataObjPrinting->pluck('qty'))
                            ->dataset('Cutting', $dataObjCutting->pluck('qty'));

        $params = [            
            'fromdate' =>  $fromdate,
            'todate' =>  $todate, 
            'oee_chart' => $oee_chart, 
            'quality_chart' => $quality_chart,
            'chart' => $chart,  
            'chart2' => $chart2,  
        ];

        return view('rotary.dashboard.dashboard')->with($params);
    }
}
