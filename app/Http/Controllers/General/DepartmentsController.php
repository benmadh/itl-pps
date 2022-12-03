<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Larashop\Models\General\Department;

class DepartmentsController extends Controller
{
    public function __construct(){
        $this->middleware('permission:departments_access_allow');
        $this->middleware('permission:departments_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:departments_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:departments_delete', ['only' => ['show', 'delete']]);
    }  

    public function index(){ 
        $dataLst = Department::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();

        $params = [
            'title' => 'Departments Listing',
            'dataLst' => $dataLst,           
        ];

        return view('general.departments.list')->with($params);
    }

    public function create(){               
        $params = [
            'title' => 'Create Departments', 
        ];
        return view('general.departments.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:departments',                        
        ]);

        $dataObjIns = Department::create([            
            'name' => trim($request->input('name')),
            'code' => trim($request->input('code')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('departments.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = Department::findOrFail($id);
        $params = [
            'title' => 'Edit Departments',
            'dataObj' => $dataObj,
        ];
        return view('general.departments.edit')->with($params);
    }


    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:departments,name,'.$id,          
        ]);

        $dataObjUpd = Department::findOrFail($id);       
        $dataObjUpd->name = trim($request->input('name')); 
        $dataObjUpd->code = trim($request->input('code'));      
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('departments.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = Department::findOrFail($id);
            $params = [
                'title' => 'Delete Departments',
                'dataObj' => $dataObj,
            ];
            return view('general.departments.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = Department::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = Department::findOrFail($id);
        $dataObjDel->delete();
        return redirect()->route('departments.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'name' => $request['name'],
            'code' => $request['code'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('general/departments/list', ['dataLst' => $dataLst, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = Department::where('deleted_at', '=', null)->orderBy('id', 'DESC');
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
            $index++;
        }       
        return $query->paginate(100000);
    }

}
