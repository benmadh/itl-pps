<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Illuminate\Support\Collection;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Larashop\Models\General\Machine;
use Larashop\Models\General\Department;
use Larashop\Models\General\MachineType;
use Larashop\Models\General\WheelType;
use Larashop\Models\General\Company;
use Larashop\Models\General\Location;
use Larashop\Models\General\Condition;
use Larashop\Models\General\Cylinder;
use Larashop\Models\General\MachineCategory;

class MachinesController extends Controller
{ 
    
    public $arr_dep = array('12','13','14','15','16','17','18');
    public function __construct(){
        $this->middleware('permission:machines_access_allow');
        $this->middleware('permission:machines_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:machines_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:machines_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = Machine::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
        
        $dataMcTypes = MachineType::where('deleted_at', '=', null)->get();
        $dataCompanies = Company::where('deleted_at', '=', null)->get();
        $dataLocations = Location::where('deleted_at', '=', null)->get();
        $dataConditions = Condition::where('deleted_at', '=', null)->get();
        $dataMachineCat = MachineCategory::where('deleted_at', '=', null)->get();
        $dataDepts = Department::where('deleted_at', '=', null)
                     ->whereIn('id', $this->arr_dep)
                     ->get(); 

        $params = [
            'title' => 'Machine List',
            'dataLst' => $dataLst,  
            'dataDepts' => $dataDepts, 
            'dataMcTypes' => $dataMcTypes, 
            'dataMachineCat' => $dataMachineCat, 
            'dataCompanies' => $dataCompanies, 
            'dataLocations' => $dataLocations,
            'dataConditions' => $dataConditions,
        ];

        return view('general.machines.list')->with($params);
    }

    public function create(){  
        $dataDepts = Department::where('deleted_at', '=', null)
                     ->whereIn('id', $this->arr_dep)
                     ->get(); 
        $dataMcTypes = MachineType::where('deleted_at', '=', null)->get();
        $dataWheelTypes = WheelType::where('deleted_at', '=', null)->get();
        $dataCompanies = Company::where('deleted_at', '=', null)->get();
        $dataLocations = Location::where('deleted_at', '=', null)->get();
        $dataConditions = Condition::where('deleted_at', '=', null)->get();
        $cylinderData = Cylinder::where('deleted_at', '=', null)->get();
        $dataMachineCat = MachineCategory::where('deleted_at', '=', null)->get();
        
        $params = [
            'title' => 'Create Machine', 
            'dataDepts' => $dataDepts, 
            'dataMcTypes' => $dataMcTypes, 
            'dataWheelTypes' => $dataWheelTypes, 
            'dataCompanies' => $dataCompanies, 
            'dataLocations' => $dataLocations,
            'dataConditions' => $dataConditions,
            'cylinderData' => $cylinderData, 
            'dataMachineCat' => $dataMachineCat,            
        ];
        return view('general.machines.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'machin_number' => 'required|unique:machines', 
            'asset_number' => 'required', 
            'no_of_colour_front' => 'required|numeric', 
            'no_of_colour_back' => 'required|numeric',
            'rpm' => 'required|numeric', 
            'department_id' => 'required', 
            'machine_type_id' => 'required', 
            'wheel_type_id' => 'required', 
            'condition_id' => 'required', 
            'company_id' => 'required', 
            'location_id' => 'required',
            'machine_category_id' => 'required',  
        ]);
        $condition_id=$request->input('condition_id');
        if($condition_id==1){
            $isActive=true;
        }else{
            $isActive=false;
        }
        
        $dataObjIns = Machine::create([
            'machin_number' => trim($request->input('machin_number')),
            'asset_number' => trim($request->input('asset_number')),
            'no_of_colour_front' => $request->input('no_of_colour_front'),
            'no_of_colour_back' => $request->input('no_of_colour_back'),
            'rpm' => $request->input('no_of_colour_front'),            
            'department_id' => $request->input('department_id'),
            'machine_type_id' => $request->input('machine_type_id'),
            'machine_category_id' => $request->input('machine_category_id'),
            'wheel_type_id' => $request->input('wheel_type_id'),
            'condition_id' => $request->input('condition_id'),
            'company_id' => $request->input('company_id'),
            'location_id' => $request->input('location_id'),
            'isActive' => $isActive,
            'created_by'=>Auth::user()->id,
        ]);
        $dataObjIns->cylinders()->attach($request->cylinders_id);  

        return redirect()->route('machines.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = Machine::findOrFail($id);
        $dataDepts = Department::where('deleted_at', '=', null)
                     ->whereIn('id', $this->arr_dep)
                     ->get(); 
        $dataMcTypes = MachineType::where('deleted_at', '=', null)->get();
        $dataWheelTypes = WheelType::where('deleted_at', '=', null)->get();
        $dataCompanies = Company::where('deleted_at', '=', null)->get();
        $dataLocations = Location::where('deleted_at', '=', null)->get();
        $dataConditions = Condition::where('deleted_at', '=', null)->get();
        $cylinderData = Cylinder::where('deleted_at', '=', null)->get();
        $dataMachineCat = MachineCategory::where('deleted_at', '=', null)->get();
        $params = [
            'title' => 'Edit Machine',
            'dataObj' => $dataObj,
            'dataDepts' => $dataDepts, 
            'dataMcTypes' => $dataMcTypes, 
            'dataWheelTypes' => $dataWheelTypes, 
            'dataCompanies' => $dataCompanies, 
            'dataLocations' => $dataLocations,
            'dataConditions' => $dataConditions,
            'cylinderData' => $cylinderData,
            'dataMachineCat' => $dataMachineCat, 
        ];
        return view('general.machines.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'machin_number' => 'required|unique:machines,machin_number,'.$id,
            'asset_number' => 'required', 
            'no_of_colour_front' => 'required|numeric', 
            'no_of_colour_back' => 'required|numeric',
            'rpm' => 'required|numeric', 
            'department_id' => 'required', 
            'machine_type_id' => 'required', 
            'wheel_type_id' => 'required', 
            'condition_id' => 'required', 
            'company_id' => 'required', 
            'location_id' => 'required',
            'machine_category_id' => 'required',  
        ]);

        if ($request->hasFile('picture')) {
          $file = array('picture' => $request->file('picture'));
          $destinationPath = 'upload/images/machines/'; // upload path
          $extension = $request->file('picture')->getClientOriginalExtension(); 
          $fileName = $request->input('machin_number').'.'.Str::lower($extension); // renaming image
          $request->file('picture')->move($destinationPath, $fileName);         
        }else{
          $fileName = null;         
        }
       
        $dataObjUpd = Machine::findOrFail($id);        
        $dataObjUpd->machin_number = trim($request->input('machin_number'));
        $dataObjUpd->asset_number = trim($request->input('asset_number'));  
        $dataObjUpd->no_of_colour_front = $request->input('no_of_colour_front'); 
        $dataObjUpd->no_of_colour_back = $request->input('no_of_colour_back'); 
        $dataObjUpd->rpm = $request->input('rpm'); 
        $dataObjUpd->isActive = $request->has('isActive') ? true : false;  
        $dataObjUpd->department_id = $request->input('department_id'); 
        $dataObjUpd->machine_type_id = $request->input('machine_type_id'); 
        $dataObjUpd->wheel_type_id = $request->input('wheel_type_id');  
        $dataObjUpd->condition_id = $request->input('condition_id');
        $dataObjUpd->company_id = $request->input('company_id');
        $dataObjUpd->location_id = $request->input('location_id'); 
        $dataObjUpd->machine_category_id = $request->input('machine_category_id');  
        $dataObjUpd->updated_by =  Auth::user()->id;
        if(!$fileName == null){
            $dataObjUpd->photo= $fileName;                  
        }    
        $dataObjUpd->save();
        $dataObjUpd->cylinders()->detach($dataObjUpd->cylinders);
        $dataObjUpd->cylinders()->attach($request->cylinders_id);
        return redirect()->route('machines.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = Machine::findOrFail($id);
            $params = [
                'title' => 'Delete Machine',
                'dataObj' => $dataObj,
            ];
            return view('general.machines.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = Machine::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = Machine::findOrFail($id);
        $dataObjDel->delete();

        $dataObjDel->cylinders()->detach($dataObjDel->cylinders); 

        return redirect()->route('machines.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'machin_number' => $request['machin_number'], 
            'asset_number' => $request['asset_number'],
            'company_id' => $request['company_id'],
            'location_id' => $request['location_id'],
            'department_id' => $request['department_id'],
            'condition_id' => $request['condition_id'],
            'machine_type_id' => $request['machine_type_id'],  
            'machine_category_id' => $request['machine_category_id'],                     
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        $dataDepts = Department::where('deleted_at', '=', null)
                     ->whereIn('id', $this->arr_dep)
                     ->get(); 
        $dataMcTypes = MachineType::where('deleted_at', '=', null)->get();
        $dataCompanies = Company::where('deleted_at', '=', null)->get();
        $dataLocations = Location::where('deleted_at', '=', null)->get();
        $dataConditions = Condition::where('deleted_at', '=', null)->get();
        $dataMachineCat = MachineCategory::where('deleted_at', '=', null)->get();

        return view('general/machines/list', ['dataLst' => $dataLst, 
                                                'dataDepts' => $dataDepts, 
                                                'dataMcTypes' => $dataMcTypes,  
                                                'dataCompanies' => $dataCompanies, 
                                                'dataLocations' => $dataLocations,
                                                'dataMachineCat' => $dataMachineCat,
                                                'dataConditions' => $dataConditions,
                                                'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = Machine::where('deleted_at', '=', null)->orderBy('id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {                
                if ($fields[$index] == 'machin_number') {
                    if ($constraint != null) { 
                        $query = $query->where('machin_number', 'like', '%'.trim($constraint).'%');
                    } 
                }  
                if ($fields[$index] == 'asset_number') {
                    if ($constraint != null) { 
                        $query = $query->where('asset_number', 'like', '%'.trim($constraint).'%');
                    } 
                } 
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
                if($fields[$index] == 'department_id'){
                    if ($constraint != null) { 
                        $query = $query->where('department_id', '=', $constraint); 
                    }
                } 
                if($fields[$index] == 'condition_id'){
                    if ($constraint != null) { 
                        $query = $query->where('condition_id', '=', $constraint); 
                    }
                } 
                if($fields[$index] == 'machine_type_id'){
                    if ($constraint != null) { 
                        $query = $query->where('machine_type_id', '=', $constraint); 
                    }
                } 

                if($fields[$index] == 'machine_category_id'){
                    if ($constraint != null) { 
                        $query = $query->where('machine_category_id', '=', $constraint); 
                    }
                }
            $index++;
        }       
        return $query->paginate(100000);
    }

    public function getMachineList($department_id) {          
        $dataObj = Machine::where('department_id', '=', $department_id)->where('machine_category_id', '=', 1)->orderBy('machin_number', 'ASC')->get(); 
        return Response::json($dataObj); 
    }
}
