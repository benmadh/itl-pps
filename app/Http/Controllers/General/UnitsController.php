<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;

use Larashop\Models\General\Unit;

class UnitsController extends Controller
{
    public function __construct(){
        $this->middleware('permission:units_access_allow');
        $this->middleware('permission:units_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:units_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:units_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = Unit::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
                            
        $params = [
            'title' => 'Units List',
            'dataLst' => $dataLst,           
        ];

        return view('general.units.list')->with($params);
    }

    public function create(){               
        $params = [
            'title' => 'Create Unit', 
        ];
        return view('general.units.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'code' => 'required|unique:units',
            'name' => 'required',                   
        ]);

        $dataObjIns = Unit::create([
            'code' => trim($request->input('code')),
            'name' => trim($request->input('name')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('units.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = Unit::findOrFail($id);
        $params = [
            'title' => 'Edit Unit',
            'dataObj' => $dataObj,
        ];
        return view('general.units.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'code' => 'required|unique:units,code,'.$id,
            'name' => 'required', 
        ]);

        $dataObjUpd = Unit::findOrFail($id);        
        $dataObjUpd->code = trim($request->input('code'));  
        $dataObjUpd->name = trim($request->input('name'));       
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('units.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = Unit::findOrFail($id);
            $params = [
                'title' => 'Delete Unit',
                'dataObj' => $dataObj,
            ];
            return view('general.units.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = Unit::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = Unit::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('units.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'code' => $request['code'],
            'name' => $request['name'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('general/units/list', ['dataLst' => $dataLst, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = Unit::where('deleted_at', '=', null)->orderBy('id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {                
            if ($fields[$index] == 'code') {
                if ($constraint != null) { 
                    $query = $query->where('code', 'like', '%'.trim($constraint).'%');
                } 
            }  
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
