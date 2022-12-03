<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;

use Larashop\Models\General\Cylinder;
use Larashop\Models\General\Length;

class CylindersController extends Controller
{
    public function __construct(){
        $this->middleware('permission:cylinders_access_allow');
        $this->middleware('permission:cylinders_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:cylinders_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:cylinders_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = Cylinder::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(50)                                   
                             ->get();
                            
        $params = [
            'title' => 'Cylinder List',
            'dataLst' => $dataLst,           
        ];

        return view('general.cylinders.list')->with($params);
    }

    public function create(){ 
        $lengths = Length::where('deleted_at', '=', null)->get();             
        $params = [
            'title' => 'Create Cylinder', 
            'lengths' => $lengths, 
        ];
        return view('general.cylinders.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:cylinders', 
            'lengths_id' => 'required', 
            'wheel_no' => 'required',                
        ]);

        $dataObjIns = Cylinder::create([
            'name' => trim($request->input('name')),
            'wheel_no' => trim($request->input('wheel_no')),
            'created_by'=>Auth::user()->id,
        ]);
        $dataObjIns->lengths()->attach($request->lengths_id);        

        return redirect()->route('cylinders.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = Cylinder::findOrFail($id);
        $lengths = Length::all(); 
        $params = [
            'title' => 'Edit Cylinder',
            'dataObj' => $dataObj,
            'lengths' => $lengths,
        ];
        return view('general.cylinders.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:cylinders,name,'.$id,
            'lengths_id' => 'required', 
            'wheel_no' => 'required',     
        ]);

        $dataObjUpd = Cylinder::findOrFail($id);        
        $dataObjUpd->name = trim($request->input('name')); 
        $dataObjUpd->wheel_no = trim($request->input('wheel_no'));       
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();
        $dataObjUpd->lengths()->detach($dataObjUpd->lengths);
        $dataObjUpd->lengths()->attach($request->lengths_id);
        return redirect()->route('cylinders.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = Cylinder::findOrFail($id);
            $params = [
                'title' => 'Delete Cylinder',
                'dataObj' => $dataObj,
            ];
            return view('general.cylinders.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = Cylinder::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = Cylinder::findOrFail($id);
        $dataObjDel->delete();

        $dataObjDel->lengths()->detach($dataObjDel->lengths); 

        return redirect()->route('cylinders.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'name' => $request['name'],    
            'wheel_no' => $request['wheel_no'],                   
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('general/cylinders/list', ['dataLst' => $dataLst, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = Cylinder::where('deleted_at', '=', null)->orderBy('id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {                
            if ($fields[$index] == 'name') {
                if ($constraint != null) { 
                    $query = $query->where('name', 'like', '%'.trim($constraint).'%');
                } 
            }   
            if ($fields[$index] == 'wheel_no') {
                if ($constraint != null) { 
                    $query = $query->where('wheel_no', 'like', '%'.trim($constraint).'%');
                } 
            }  
            $index++;
        }       
        return $query->paginate(100000);
    }
}
