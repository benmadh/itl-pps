<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;

use Larashop\Models\General\LabelType;

class LabelTypesController extends Controller
{
    public function __construct(){
        $this->middleware('permission:label_type_access_allow');
        $this->middleware('permission:label_type_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:label_type_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:label_type_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = LabelType::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
                            
        $params = [
            'title' => 'Label Type List',
            'dataLst' => $dataLst,           
        ];

        return view('general.labeltypes.list')->with($params);
    }

    public function create(){               
        $params = [
            'title' => 'Create Label Type', 
        ];
        return view('general.labeltypes.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:label_types',                   
        ]);

        $dataObjIns = LabelType::create([
            'name' => trim($request->input('name')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('labeltypes.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = LabelType::findOrFail($id);
        $params = [
            'title' => 'Edit Label Type',
            'dataObj' => $dataObj,
        ];
        return view('general.labeltypes.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:label_types,name,'.$id,
        ]);

        $dataObjUpd = LabelType::findOrFail($id);        
        $dataObjUpd->name = trim($request->input('name'));       
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('labeltypes.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = LabelType::findOrFail($id);
            $params = [
                'title' => 'Delete Label Type',
                'dataObj' => $dataObj,
            ];
            return view('general.labeltypes.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = LabelType::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = LabelType::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('labeltypes.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'name' => $request['name'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('general/labeltypes/list', ['dataLst' => $dataLst, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = LabelType::where('deleted_at', '=', null)->orderBy('id', 'DESC');
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
