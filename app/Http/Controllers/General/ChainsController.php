<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;

use Larashop\Models\General\Chain;

class ChainsController extends Controller
{
    public function __construct(){
        // $this->middleware('permission:chains_access_allow');
        // $this->middleware('permission:chains_create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:chains_edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:chains_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = Chain::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
                            
        $params = [
            'title' => 'chains List',
            'dataLst' => $dataLst,           
        ];

        return view('general.chains.list')->with($params);
    }

    public function create(){               
        $params = [
            'title' => 'Create chains', 
        ];
        return view('general.chains.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:chains',                   
        ]);

        $dataObjIns = Chain::create([
            'name' => trim($request->input('name')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('chains.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = Chain::findOrFail($id);
        $params = [
            'title' => 'Edit chain',
            'dataObj' => $dataObj,
        ];
        return view('general.chains.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:machine_types,name,'.$id,
        ]);

        $dataObjUpd = Chain::findOrFail($id);        
        $dataObjUpd->name = trim($request->input('name'));       
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('chains.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = Chain::findOrFail($id);
            $params = [
                'title' => 'Delete chain',
                'dataObj' => $dataObj,
            ];
            return view('general.chains.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = Chain::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = Chain::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('chains.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'name' => $request['name'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('general/chains/list', ['dataLst' => $dataLst, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = Chain::where('deleted_at', '=', null)->orderBy('id', 'DESC');
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
