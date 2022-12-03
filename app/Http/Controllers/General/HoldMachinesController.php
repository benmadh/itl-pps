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

use Larashop\Models\General\Department;
use Larashop\Models\General\Machine;
use Larashop\Models\General\McHoldReason;
use Larashop\Models\General\HoldMachine;

Use Larashop\Http\Controllers\General\UtilityHelperGeneral;

class HoldMachinesController extends Controller
{
    use UtilityHelperGeneral;
    public $arr_dep = array('12','13','14','15','16','17','18');
    // public function __construct(){
    //     $this->middleware('permission:hold_machine_access_allow');
    //     $this->middleware('permission:hold_machine_create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:hold_machine_edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:hold_machine_delete', ['only' => ['show', 'delete']]);
    // }

    public function index(){ 
        $dataLst = HoldMachine::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get(); 

        $date = Carbon::now()->toDateString();

        $dataDepts = Department::where('deleted_at', '=', null)
                     ->whereIn('id', $this->arr_dep)
                     ->get();        
        $dataReason = McHoldReason::where('deleted_at', '=', null)->get();
        $shiftList = $this->genShift(null); 
        $params = [
            'title' => 'List of Machine Hold Status for Date & Shift',
            'dataLst' => $dataLst,
            'dataDepts' => $dataDepts, 
            'dataReason' => $dataReason, 
            'date' => $date, 
            'shiftList' => $shiftList,
        ];

        return view('general.holdmachines.list')->with($params);
    }

    public function create(){

        $date = Carbon::now()->toDateString();        
        $dataDepts = Department::where('deleted_at', '=', null)
                     ->whereIn('id', $this->arr_dep)
                     ->get();        
        $dataReason = McHoldReason::where('deleted_at', '=', null)->get();
        $shiftList = $this->genShift(null); 
        $params = [
            'title' => 'Create Machine Hold Status for Date & Shift', 
            'dataDepts' => $dataDepts, 
            'dataReason' => $dataReason, 
            'date' => $date, 
            'shiftList' => $shiftList,
        ];      
        return view('general.holdmachines.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'date' => 'required|date',
            'shift' => 'required',  
            'mc_hold_reason_id' => 'required',                     
            'machine_id' => 'required', 
            'department_id' => 'required',  
        ]);

        $dataObjIns = HoldMachine::create([
            'date' => $request->input('date'),
            'shift' => $request->input('shift'),
            'mc_hold_reason_id' => $request->input('mc_hold_reason_id'),
            'machine_id' => $request->input('machine_id'),
            'department_id' => $request->input('department_id'),
            'company_id' =>Auth::user()->locations->companies->id,
            'location_id' =>  Auth::user()->locations->id,       
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('holdmachines.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->date]));
    }

    public function edit($id) {               
        $dataObj = HoldMachine::findOrFail($id);
        $dataDepts = Department::where('deleted_at', '=', null)
                     ->whereIn('id', $this->arr_dep)
                     ->get();        
        $dataReason = McHoldReason::where('deleted_at', '=', null)->get();
        $shiftList = $this->genShift(null); 

        $machineLst = Machine::where('deleted_at', '=', null)
                            ->where('department_id', '=', $dataObj->department_id)
                            ->where('machine_category_id', '=', 1)
                            ->orderBy('machin_number', 'asc') 
                            ->get();
        $params = [
            'title' => 'Edit Machine Hold Status for Date & Shift',
            'dataObj' => $dataObj,
            'dataDepts' => $dataDepts, 
            'dataReason' => $dataReason,
            'shiftList' => $shiftList,
            'machineLst' => $machineLst,
        ];
        return view('general.holdmachines.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'date' => 'required|date',
            'shift' => 'required',  
            'mc_hold_reason_id' => 'required',                     
            'machine_id' => 'required', 
            'department_id' => 'required',  
        ]);

        $dataObjUpd = HoldMachine::findOrFail($id);        
        $dataObjUpd->date = $request->input('date'); 
        $dataObjUpd->shift = $request->input('shift');
        $dataObjUpd->mc_hold_reason_id = $request->input('mc_hold_reason_id');
        $dataObjUpd->machine_id = $request->input('machine_id'); 
        $dataObjUpd->department_id = $request->input('department_id');  
        $dataObjUpd->company_id = Auth::user()->locations->companies->id;
        $dataObjUpd->location_id = Auth::user()->locations->id;   
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('holdmachines.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->date]));
    }

    public function show($id){
        try{
            $dataObj = HoldMachine::findOrFail($id);
            $params = [
                'title' => 'Delete Machine Hold Status for Date & Shift',
                'dataObj' => $dataObj,
            ];
            return view('general.holdmachines.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = HoldMachine::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = HoldMachine::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('holdmachines.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'date' => $request['date'], 
            'department_id' => $request['department_id'], 
            'shift' => $request['shift'],   
            'mc_hold_reason_id' => $request['mc_hold_reason_id'],  
            'machine_id' => $request['machine_id'],                
        ];        
        $dataLst = $this->doSearchingQuery($constraints);
        $date = $request['date']; 
        $dataDepts = Department::where('deleted_at', '=', null)
                     ->whereIn('id', $this->arr_dep)
                     ->get();
        $shiftList = $this->genShift(null); 
        $dataReason = McHoldReason::where('deleted_at', '=', null)->get();
        return view('general/holdmachines/list', ['dataLst' => $dataLst,'dataReason' => $dataReason, 'shiftList' => $shiftList, 'dataDepts' => $dataDepts,'date' => $date,'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = HoldMachine::where('deleted_at', '=', null)->orderBy('id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {                
                if ($fields[$index] == 'date') {
                    if ($constraint != null) { 
                        $query = $query->where('date', '=', $constraint);
                    } 
                }

                if($fields[$index] == 'department_id'){
                    if ($constraint != null) { 
                        $query = $query->where('department_id', '=', $constraint); 
                    }
                }

                if($fields[$index] == 'shift'){
                    if ($constraint != null) { 
                        $query = $query->where('shift', '=', $constraint); 
                    }
                }

                if($fields[$index] == 'mc_hold_reason_id'){
                    if ($constraint != null) { 
                        $query = $query->where('mc_hold_reason_id', '=', $constraint); 
                    }
                }

                if($fields[$index] == 'machine_id'){
                    if ($constraint != null) { 
                        $query = $query->where('machine_id', '=', $constraint); 
                    }
                }

            $index++;
        }       
        return $query->paginate(100000);
    }

}
