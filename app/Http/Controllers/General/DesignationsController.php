<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Larashop\Models\General\Designation;
use Larashop\Models\General\Level;

class DesignationsController extends Controller
{
    public function __construct(){
        $this->middleware('permission:designations_access_allow');
        $this->middleware('permission:designations_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:designations_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:designations_delete', ['only' => ['show', 'delete']]);
    }  

    public function index(){ 

        $dataLst = Designation::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();

        $dataLevels = Level::where('deleted_at', '=', null)->get(); 

        $params = [
            'title' => 'Designations Listing',
            'dataLst' => $dataLst,  
            'dataLevels' => $dataLevels,          
        ];

        return view('general.designations.list')->with($params);
    }

    public function create(){ 
        $dataLevels = Level::where('deleted_at', '=', null)->get();               
        $params = [
            'title' => 'Create Designations', 
            'dataLevels' => $dataLevels,
        ];
        return view('general.designations.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:designations', 
            'levels_id' => 'required',                       
        ]);

        $dataObjIns = Designation::create([            
            'name' => trim($request->input('name')),
            'code' => trim($request->input('code')),
            'levels_id' => $request->input('levels_id'),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('designations.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = Designation::findOrFail($id);
        $dataLevels = Level::where('deleted_at', '=', null)->get();
        $params = [
            'title' => 'Edit Designations',
            'dataObj' => $dataObj,
            'dataLevels' => $dataLevels,
        ];
        return view('general.designations.edit')->with($params);
    }


    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:designations,name,'.$id, 
            'levels_id' => 'required',          
        ]);

        $dataObjUpd = Designation::findOrFail($id);       
        $dataObjUpd->name = trim($request->input('name')); 
        $dataObjUpd->code = trim($request->input('code')); 
        $dataObjUpd->levels_id = $request->input('levels_id');      
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('designations.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = Designation::findOrFail($id);
            $params = [
                'title' => 'Delete Designations',
                'dataObj' => $dataObj,
            ];
            return view('general.designations.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){
        $dataObjUpd = Designation::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = Designation::findOrFail($id);
        $dataObjDel->delete();
        return redirect()->route('designations.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'name' => $request['name'],
            'code' => $request['code'],   
            'levels_id' => $request['levels_id'],                   
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        $dataLevels = Level::where('deleted_at', '=', null)->get();
        return view('general/designations/list', ['dataLst' => $dataLst, 'dataLevels' => $dataLevels, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = Designation::where('deleted_at', '=', null)->orderBy('id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {                
               if ($fields[$index] == 'name') {
                    if ($constraint != null) { 
                        $query = $query->where('name', 'like', '%'.trim($constraint).'%');
                    } 
                }                            
                if ($fields[$index] == 'code') {
                    if ($constraint != null) { 
                        $query = $query->where('code', 'like', '%'.trim($constraint).'%');                       
                    } 
                }

                if ($fields[$index] == 'levels_id') {
                    if ($constraint != null) { 
                        $query = $query->where('levels_id', '=', $constraint);                       
                    } 
                } 

            $index++;
        }       
        return $query->paginate(100000);
    }

}
