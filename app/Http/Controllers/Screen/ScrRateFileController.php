<?php

namespace Larashop\Http\Controllers\Screen;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Illuminate\Support\Collection;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Larashop\Models\General\MachineType;
use Larashop\Models\General\Company;
use Larashop\Models\General\Department;
use Larashop\Models\General\Location;
use Larashop\Models\General\CuttingMethod;
use Larashop\Models\General\SubstrateCategory;

use Larashop\Models\General\WorkOrderHeader;
use Larashop\Models\General\WorkOrderLine;

use Larashop\Models\Rotary\MrnRateFileRotary;
use Larashop\Models\Rotary\AdditionsToCusRotary;
use Larashop\Models\Rotary\CuttingWasteRotary;
use Larashop\Models\Rotary\RunningWasteRotary;
use Larashop\Models\Rotary\MachineSpeedRotary;

Use Larashop\Http\Controllers\General\UtilityHelperGeneral;

class ScrRateFileController extends Controller
{
     use UtilityHelperGeneral;

    public function __construct(){
        $this->middleware('permission:scr_rate_access_allow');
        $this->middleware('permission:scr_rate_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:scr_rate_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:scr_rate_delete', ['only' => ['show', 'delete']]);
    }
    
       

    public function index(){         
        $department_id=13;
        $dataLst = MrnRateFileRotary::where('deleted_at', '=', null)->where('department_id', '=', $department_id)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();                           
       
        $mctData = MachineType::where('deleted_at', '=', null)->get();
        $comData = Company::where('deleted_at', '=', null)->get();
        // $deptData = Department::where('deleted_at', '=', null)
        //              ->whereIn('id',array('12','13'))
        //              ->get(); 
        $locData = Location::where('deleted_at', '=', null)->get();
        $tableTypeLst = $this->genTableType(null);

        $params = [
            'title' => 'PFL -Screen Rate File List',
            'dataLst' => $dataLst,
            'mctData' => $mctData,  
            'comData' => $comData, 
            'locData' => $locData,
            'tableTypeLst' => $tableTypeLst,
        ];

        return view('screen.pflratefile.list')->with($params);
    }

    public function create(){  

        $mctData = MachineType::where('deleted_at', '=', null)->get();
        $comData = Company::where('deleted_at', '=', null)->get();
        $locData = Location::where('deleted_at', '=', null)->get();
        $cuttingMethodData = CuttingMethod::where('deleted_at', '=', null)->get();
        $substrateCategoryData = SubstrateCategory::where('deleted_at', '=', null)->get();
        $tableTypeLst = $this->genTableType(null);
        
        $params = [
            'title' => 'Create Screen Rates', 
            'mctData' => $mctData, 
            'comData' => $comData, 
            'locData' => $locData, 
            'cuttingMethodData' => $cuttingMethodData, 
            'substrateCategoryData' => $substrateCategoryData,
            'tableTypeLst' => $tableTypeLst,
        ];

        return view('screen.pflratefile.create')->with($params);
    }

    public function store(Request $request){

        $this->validate($request, [
            'company_id' => 'required', 
            'location_id' => 'required', 
            'machine_type_id' => 'required', 
            'table_type' => 'required', 
            'each_clr_front_waste_mtr' => 'required|numeric',
            'each_clr_front_setup_time_min' => 'numeric',
            'each_clr_back_waste_mtr' => 'numeric',
            'each_clr_back_setup_time_min' => 'numeric',
            'plate_change_waste_mtr' => 'numeric',
            'plate_change_setup_time_min' => 'numeric',
            'tape_setup_waste_mtr' => 'numeric',
            'tape_setup_setup_time_min' => 'numeric',
            'reference_change_time_min' => 'numeric', 
        ]);       
        $department_id=13;
        $dataObjIns = MrnRateFileRotary::create([
            'company_id' => trim($request->input('company_id')),
            'location_id' => trim($request->input('location_id')),
            'machine_type_id' => $request->input('machine_type_id'),
            'department_id' => $department_id,
            'table_type' => $request->input('table_type'),
            'each_clr_front_waste_mtr' => $request->input('each_clr_front_waste_mtr'),            
            'each_clr_front_setup_time_min' => $request->input('each_clr_front_setup_time_min'),
            'each_clr_back_waste_mtr' => $request->input('each_clr_back_waste_mtr'),
            'each_clr_back_setup_time_min' => $request->input('each_clr_back_setup_time_min'),
            'plate_change_waste_mtr' => $request->input('plate_change_waste_mtr'),
            'plate_change_setup_time_min' => $request->input('plate_change_setup_time_min'),
            'tape_setup_waste_mtr' => $request->input('tape_setup_waste_mtr'),
            'tape_setup_setup_time_min' => $request->input('tape_setup_setup_time_min'),
            'reference_change_time_min' => $request->input('reference_change_time_min'),
            'created_by'=>Auth::user()->id,
        ]);  

        $mrnRateFile_id=$dataObjIns->id;
        foreach ($request->from_ord_qty as $key => $v) { 
            $dataAdditionsToCus = array('from_ord_qty'=> $request->from_ord_qty[$key],                  
                          'to_ord_qty'=> $request->to_ord_qty[$key],
                          'percentage'=> $request->percentage[$key], 
                          'mrn_rate_file_rotaries_id'=> $mrnRateFile_id,
                          'created_at'=> date('Y-m-d H:i:s'),                   
                          'created_by'=> Auth::user()->id);  
            AdditionsToCusRotary::insert($dataAdditionsToCus);     

        } 

        foreach ($request->cm_id as $keyCm => $vCm) { 
            $dataCuttingWaste = array('pcs'=> $request->pcs[$keyCm],                  
                          'cutting_methods_id'=> $request->cm_id[$keyCm],                         
                          'mrn_rate_file_rotaries_id'=> $mrnRateFile_id,
                          'created_at'=> date('Y-m-d H:i:s'),                   
                          'created_by'=> Auth::user()->id);  
            CuttingWasteRotary::insert($dataCuttingWaste);     

        } 

        $substrateCategoryData = SubstrateCategory::where('deleted_at', '=', null)->get();        
        foreach ($request->from_ord_qty_rw as $keyRw => $vRw) { 
            foreach ($substrateCategoryData as $row) {
                $sb_id=$row->id;
                $dataRunningWaste = array('from_ord_qty'=> $request->from_ord_qty_rw[$keyRw],                  
                          'to_ord_qty'=> $request->to_ord_qty_rw[$keyRw], 
                          'batch_size_cat_ref'=> $request->batch_size_cat_ref[$keyRw],
                          'substrate_category_id'=> $sb_id,
                          'percentage'=> $request->$sb_id[$keyRw],                        
                          'mrn_rate_file_rotaries_id'=> $mrnRateFile_id,
                          'created_at'=> date('Y-m-d H:i:s'),                   
                          'created_by'=> Auth::user()->id);  
                RunningWasteRotary::insert($dataRunningWaste); 
            } 
        } 

        return redirect()->route('scrratefile.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->companies->name]));
    }

    public function edit($id) {  

        $dataObj = MrnRateFileRotary::findOrFail($id);        
        $mctData = MachineType::where('deleted_at', '=', null)->get();
        $comData = Company::where('deleted_at', '=', null)->get();
        $locData = Location::where('deleted_at', '=', null)->get();
        $cuttingMethodData = CuttingMethod::where('deleted_at', '=', null)->get();
        $substrateCategoryData = SubstrateCategory::where('deleted_at', '=', null)->get();
        $tableTypeLst = $this->genTableType(null);
        
        $dataRw = DB::table('running_waste_rotaries')
                               ->leftJoin('substrate_categories', 'running_waste_rotaries.substrate_category_id', '=', 'substrate_categories.id')
                               ->select(DB::raw('DISTINCT running_waste_rotaries.from_ord_qty, running_waste_rotaries.to_ord_qty, running_waste_rotaries.batch_size_cat_ref'))
                               ->addSelect(DB::raw('MAX(IF(running_waste_rotaries.substrate_category_id = 1, running_waste_rotaries.percentage, NULL)) as `1`'))
                               ->addSelect(DB::raw('MAX(IF(running_waste_rotaries.substrate_category_id = 2, running_waste_rotaries.percentage, NULL)) as `2`'))
                               ->addSelect(DB::raw('MAX(IF(running_waste_rotaries.substrate_category_id = 3, running_waste_rotaries.percentage, NULL)) as `3`'))
                               ->addSelect(DB::raw('MAX(IF(running_waste_rotaries.substrate_category_id = 4, running_waste_rotaries.percentage, NULL)) as `4`'))
                               ->addSelect(DB::raw('MAX(IF(running_waste_rotaries.substrate_category_id = 5, running_waste_rotaries.percentage, NULL)) as `5`'))
                               ->addSelect(DB::raw('MAX(IF(running_waste_rotaries.substrate_category_id = 6, running_waste_rotaries.percentage, NULL)) as `6`'))
                               ->addSelect(DB::raw('MAX(IF(running_waste_rotaries.substrate_category_id = 7, running_waste_rotaries.percentage, NULL)) as `7`'))
                               ->addSelect(DB::raw('MAX(IF(running_waste_rotaries.substrate_category_id = 8, running_waste_rotaries.percentage, NULL)) as `8`'))
                               ->addSelect(DB::raw('MAX(IF(running_waste_rotaries.substrate_category_id = 9, running_waste_rotaries.percentage, NULL)) as `9`'))
                               ->where('running_waste_rotaries.mrn_rate_file_rotaries_id', '=', $id )
                               ->where('running_waste_rotaries.deleted_at', '=', null)
                               ->groupBy('running_waste_rotaries.from_ord_qty')
                               ->groupBy('running_waste_rotaries.to_ord_qty')  
                               ->groupBy('running_waste_rotaries.batch_size_cat_ref')                              
                               ->get();

        $array = json_decode(json_encode($dataRw), true);
        $params = [
            'title' => 'Edit Screen Rates',
            'dataObj' => $dataObj,
            'mctData' => $mctData, 
            'comData' => $comData, 
            'locData' => $locData, 
            'cuttingMethodData' => $cuttingMethodData, 
            'substrateCategoryData' => $substrateCategoryData,
            'tableTypeLst' => $tableTypeLst,
            'dataRw' => $array,
        ];
        return view('screen.pflratefile.edit')->with($params);
    }

    public function update(Request $request, $id){
       
        $this->validate($request, [
            'company_id' => 'required', 
            'location_id' => 'required', 
            'machine_type_id' => 'required', 
            'table_type' => 'required', 
            'each_clr_front_waste_mtr' => 'required|numeric',
            'each_clr_front_setup_time_min' => 'numeric',
            'each_clr_back_waste_mtr' => 'numeric',
            'each_clr_back_setup_time_min' => 'numeric',
            'plate_change_waste_mtr' => 'numeric',
            'plate_change_setup_time_min' => 'numeric',
            'tape_setup_waste_mtr' => 'numeric',
            'tape_setup_setup_time_min' => 'numeric',
            'reference_change_time_min' => 'numeric', 
        ]);
        $department_id=13;
        $dataObjUpd = MrnRateFileRotary::findOrFail($id);        
        $dataObjUpd->company_id = trim($request->input('company_id'));
        $dataObjUpd->location_id = trim($request->input('location_id'));  
        $dataObjUpd->machine_type_id = $request->input('machine_type_id');
        $dataObjUpd->department_id = $department_id; 
        $dataObjUpd->table_type = $request->input('table_type'); 
        $dataObjUpd->each_clr_front_waste_mtr = $request->input('each_clr_front_waste_mtr'); 
        $dataObjUpd->isActive = $request->has('isActive') ? true : false;  
        $dataObjUpd->each_clr_front_setup_time_min = $request->input('each_clr_front_setup_time_min'); 
        $dataObjUpd->each_clr_back_waste_mtr = $request->input('each_clr_back_waste_mtr'); 
        $dataObjUpd->each_clr_back_setup_time_min = $request->input('each_clr_back_setup_time_min');  
        $dataObjUpd->plate_change_waste_mtr = $request->input('plate_change_waste_mtr');
        $dataObjUpd->plate_change_setup_time_min = $request->input('plate_change_setup_time_min');
        $dataObjUpd->tape_setup_waste_mtr = $request->input('tape_setup_waste_mtr');   
        $dataObjUpd->tape_setup_setup_time_min = $request->input('tape_setup_setup_time_min'); 
        $dataObjUpd->reference_change_time_min = $request->input('reference_change_time_min'); 
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        AdditionsToCusRotary::where('mrn_rate_file_rotaries_id', $id)->forceDelete();
        CuttingWasteRotary::where('mrn_rate_file_rotaries_id', $id)->forceDelete();
        RunningWasteRotary::where('mrn_rate_file_rotaries_id', $id)->forceDelete();
        

        foreach ($request->from_ord_qty as $key => $v) { 
            $dataAdditionsToCus = array('from_ord_qty'=> $request->from_ord_qty[$key],                  
                          'to_ord_qty'=> $request->to_ord_qty[$key],
                          'percentage'=> $request->percentage[$key], 
                          'mrn_rate_file_rotaries_id'=> $id,
                          'created_at'=> date('Y-m-d H:i:s'),                   
                          'created_by'=> Auth::user()->id);  
            AdditionsToCusRotary::insert($dataAdditionsToCus);     

        } 

        foreach ($request->cm_id as $keyCm => $vCm) { 
            $dataCuttingWaste = array('pcs'=> $request->pcs[$keyCm],                  
                          'cutting_methods_id'=> $request->cm_id[$keyCm],                         
                          'mrn_rate_file_rotaries_id'=> $id,
                          'created_at'=> date('Y-m-d H:i:s'),                   
                          'created_by'=> Auth::user()->id);  
            CuttingWasteRotary::insert($dataCuttingWaste);     

        } 

        $substrateCategoryData = SubstrateCategory::where('deleted_at', '=', null)->get();        
        foreach ($request->from_ord_qty_rw as $keyRw => $vRw) { 
            foreach ($substrateCategoryData as $row) {
                $sb_id=$row->id;
                $dataRunningWaste = array('from_ord_qty'=> $request->from_ord_qty_rw[$keyRw],                  
                          'to_ord_qty'=> $request->to_ord_qty_rw[$keyRw], 
                          'batch_size_cat_ref'=> $request->batch_size_cat_ref[$keyRw],
                          'substrate_category_id'=> $sb_id,
                          'percentage'=> $request->$sb_id[$keyRw],                        
                          'mrn_rate_file_rotaries_id'=> $id,
                          'created_at'=> date('Y-m-d H:i:s'),                   
                          'created_by'=> Auth::user()->id);  
                RunningWasteRotary::insert($dataRunningWaste); 
            }    
            
        } 
        
        return redirect()->route('scrratefile.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->companies->name]));
    }

    public function show($id){
        try{
            $dataObj = MrnRateFileRotary::findOrFail($id);
            $params = [
                'title' => 'Delete Screen Rates',
                'dataObj' => $dataObj,
            ];
            return view('screen.pflratefile.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = MrnRateFileRotary::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = MrnRateFileRotary::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('scrratefile.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->companies->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'company_id' => $request['company_id'], 
            'location_id' => $request['location_id'],
            'machine_type_id' => $request['machine_type_id'],
            'table_type' => $request['table_type'],
            'department_id' => $request['department_id'], 
        ];

        $dataLst = $this->doSearchingQuery($constraints);

        $mctData = MachineType::where('deleted_at', '=', null)->get();
        $comData = Company::where('deleted_at', '=', null)->get();
        $locData = Location::where('deleted_at', '=', null)->get();
        // $deptData = Department::where('deleted_at', '=', null)
        //              ->whereIn('id',array('12','13'))
        //              ->get(); 

        $tableTypeLst = $this->genTableType(null);

        return view('screen/pflratefile/list', ['dataLst' => $dataLst, 
                                                'mctData' => $mctData, 
                                                'comData' => $comData, 
                                                'locData' => $locData, 
                                                'tableTypeLst' => $tableTypeLst]);
    }

    private function doSearchingQuery($constraints){            
        $query = MrnRateFileRotary::where('deleted_at', '=', null)->where('department_id', '=', 13)->orderBy('id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0;

        foreach ($constraints as $constraint) {
                if($fields[$index] == 'company_id'){
                    if ($constraint != null) { 
                        $query = $query->where('company_id', '=', $constraint); 
                    }
                } 
                if($fields[$index] == 'location_id'){
                    if ($constraint != null) { 
                        $query = $query->where('location_id', '=', $constraint); 
                    }
                } 
                if($fields[$index] == 'machine_type_id'){
                    if ($constraint != null) { 
                        $query = $query->where('machine_type_id', '=', $constraint); 
                    }
                } 
                if($fields[$index] == 'table_type'){
                    if ($constraint != null) { 
                        $query = $query->where('table_type', '=', $constraint); 
                    }
                }                            
            $index++;
        }       
        return $query->paginate(100000);
    }

    public function edit_machine_speed($id){

        try{
            $dataObj = MrnRateFileRotary::findOrFail($id);   
            $substrateCategoryData = SubstrateCategory::where('deleted_at', '=', null)->get();           

            $dataRw = DB::table('machine_speed_rotaries')
                                   ->leftJoin('substrate_categories', 'machine_speed_rotaries.substrate_category_id', '=', 'substrate_categories.id')
                                   ->select(DB::raw('DISTINCT machine_speed_rotaries.from_ord_qty, machine_speed_rotaries.to_ord_qty, machine_speed_rotaries.batch_size_cat_ref'))
                                   ->addSelect(DB::raw('MAX(IF(machine_speed_rotaries.substrate_category_id = 1, machine_speed_rotaries.metres, NULL)) as `1`'))
                                   ->addSelect(DB::raw('MAX(IF(machine_speed_rotaries.substrate_category_id = 2, machine_speed_rotaries.metres, NULL)) as `2`'))
                                   ->addSelect(DB::raw('MAX(IF(machine_speed_rotaries.substrate_category_id = 3, machine_speed_rotaries.metres, NULL)) as `3`'))
                                   ->addSelect(DB::raw('MAX(IF(machine_speed_rotaries.substrate_category_id = 4, machine_speed_rotaries.metres, NULL)) as `4`'))
                                   ->addSelect(DB::raw('MAX(IF(machine_speed_rotaries.substrate_category_id = 5, machine_speed_rotaries.metres, NULL)) as `5`'))
                                   ->addSelect(DB::raw('MAX(IF(machine_speed_rotaries.substrate_category_id = 6, machine_speed_rotaries.metres, NULL)) as `6`'))
                                   ->addSelect(DB::raw('MAX(IF(machine_speed_rotaries.substrate_category_id = 7, machine_speed_rotaries.metres, NULL)) as `7`'))
                                   ->addSelect(DB::raw('MAX(IF(machine_speed_rotaries.substrate_category_id = 8, machine_speed_rotaries.metres, NULL)) as `8`'))
                                   ->addSelect(DB::raw('MAX(IF(machine_speed_rotaries.substrate_category_id = 9, machine_speed_rotaries.metres, NULL)) as `9`'))
                                   ->where('machine_speed_rotaries.mrn_rate_file_rotaries_id', '=', $id )
                                   ->where('machine_speed_rotaries.deleted_at', '=', null)
                                   ->groupBy('machine_speed_rotaries.from_ord_qty')
                                   ->groupBy('machine_speed_rotaries.to_ord_qty')  
                                   ->groupBy('machine_speed_rotaries.batch_size_cat_ref')                              
                                   ->get();



            $array = json_decode(json_encode($dataRw), true);
            $params = [
                'title' => 'Update Machine Speed',
                'dataObj' => $dataObj,                
                'substrateCategoryData' => $substrateCategoryData,
                'dataRw' => $array,
            ];
            return view('screen.pflratefile.edit_machine_speed')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function update_machine_speed(Request $request) {          
        $id = $request->id;
        MachineSpeedRotary::where('mrn_rate_file_rotaries_id', $id)->forceDelete();
        $substrateCategoryData = SubstrateCategory::where('deleted_at', '=', null)->get();        
        foreach ($request->from_ord_qty_rw as $keyRw => $vRw) { 
            foreach ($substrateCategoryData as $row) {
                $sb_id=$row->id;
                $dataObj = array('from_ord_qty'=> $request->from_ord_qty_rw[$keyRw],                  
                          'to_ord_qty'=> $request->to_ord_qty_rw[$keyRw], 
                          'batch_size_cat_ref'=> $request->batch_size_cat_ref[$keyRw],
                          'substrate_category_id'=> $sb_id,
                          'metres'=> $request->$sb_id[$keyRw],                        
                          'mrn_rate_file_rotaries_id'=> $id,
                          'created_at'=> date('Y-m-d H:i:s'),                   
                          'created_by'=> Auth::user()->id);  
                MachineSpeedRotary::insert($dataObj); 
            } 
        } 

        return redirect()->route('scrratefile.index')->with('success', trans('general.form.flash.updated',['name' => $request->id]));   
    }
}
