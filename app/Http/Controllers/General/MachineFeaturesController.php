<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;

use Larashop\Models\General\MachineFeatures;

class MachineFeaturesController extends Controller
{
    public function __construct(){
        $this->middleware('permission:machine_features_access_allow');
        $this->middleware('permission:machine_features_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:machine_features_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:machine_features_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = MachineFeatures::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
                            
        $params = [
            'title' => 'Machine Features List',
            'dataLst' => $dataLst,           
        ];

        return view('general.machinefeatures.list')->with($params);
    }

    public function create(){               
        $params = [
            'title' => 'Create Machine Features', 
        ];
        return view('general.machinefeatures.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:machine_features',                   
        ]);

        $dataObjIns = MachineFeatures::create([
            'name' => trim($request->input('name')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('machinefeatures.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = MachineFeatures::findOrFail($id);
        $params = [
            'title' => 'Edit Machine Features',
            'dataObj' => $dataObj,
        ];
        return view('general.machinefeatures.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:machine_features,name,'.$id,
        ]);

        $dataObjUpd = MachineFeatures::findOrFail($id);        
        $dataObjUpd->name = trim($request->input('name'));       
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('machinefeatures.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = MachineFeatures::findOrFail($id);
            $params = [
                'title' => 'Delete Machine Features',
                'dataObj' => $dataObj,
            ];
            return view('general.machinefeatures.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = MachineFeatures::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = MachineFeatures::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('machinefeatures.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }


     public function search(Request $request){
        $constraints = [            
            'name' => $request['name'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('general/machinefeatures/list', ['dataLst' => $dataLst, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = MachineFeatures::where('deleted_at', '=', null)->orderBy('id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {                
               if ($fields[$index] == 'name') {
                    if ($constraint != null) { 
                        $query = $query->where('name', 'like', '%'.trim($constraint).'%');
                    } 
                }   
            $index++;
        }       
        return $query->paginate(100000);
    }

}
