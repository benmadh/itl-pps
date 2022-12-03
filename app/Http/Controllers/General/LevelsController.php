<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Larashop\Models\General\Level;

class LevelsController extends Controller
{
    public function __construct(){
        $this->middleware('permission:levels_access_allow');
        $this->middleware('permission:levels_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:levels_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:levels_delete', ['only' => ['show', 'delete']]);
    }  

    public function index(){ 
        $dataLst = Level::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();

        $params = [
            'title' => 'Levels Listing',
            'dataLst' => $dataLst,           
        ];

        return view('general.levels.list')->with($params);
    }

    public function create(){               
        $params = [
            'title' => 'Create Levels', 
        ];
        return view('general.levels.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:levels',                        
        ]);

        $dataObjIns = Level::create([            
            'name' => trim($request->input('name')),
            'code' => trim($request->input('code')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('levels.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = Level::findOrFail($id);
        $params = [
            'title' => 'Edit Levels',
            'dataObj' => $dataObj,
        ];
        return view('general.levels.edit')->with($params);
    }


    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:levels,name,'.$id,          
        ]);

        $dataObjUpd = Level::findOrFail($id);       
        $dataObjUpd->name = trim($request->input('name')); 
        $dataObjUpd->code = trim($request->input('code'));      
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('levels.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = Level::findOrFail($id);
            $params = [
                'title' => 'Delete Levels',
                'dataObj' => $dataObj,
            ];
            return view('general.levels.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){
        $dataObjUpd = Level::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = Level::findOrFail($id);
        $dataObjDel->delete();
        return redirect()->route('levels.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'name' => $request['name'],
            'code' => $request['code'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('general/levels/list', ['dataLst' => $dataLst, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = Level::where('deleted_at', '=', null)->orderBy('id', 'DESC');
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
