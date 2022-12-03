<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;

use Larashop\Models\General\SubstrateCategory;

class SubstrateCatController extends Controller
{
    public function __construct(){
        $this->middleware('permission:substrate_category_access_allow');
        $this->middleware('permission:substrate_category_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:substrate_category_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:substrate_category_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = SubstrateCategory::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
                            
        $params = [
            'title' => 'Substrate Category List',
            'dataLst' => $dataLst,           
        ];

        return view('general.substratecategory.list')->with($params);
    }

    public function create(){               
        $params = [
            'title' => 'Create Substrate Category', 
        ];
        return view('general.substratecategory.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:substrate_categories',                   
        ]);

        $dataObjIns = SubstrateCategory::create([
            'name' => trim($request->input('name')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('substratecategories.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = SubstrateCategory::findOrFail($id);
        $params = [
            'title' => 'Edit Substrate Category',
            'dataObj' => $dataObj,
        ];
        return view('general.substratecategory.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:substrate_categories,name,'.$id,
        ]);

        $dataObjUpd = SubstrateCategory::findOrFail($id);        
        $dataObjUpd->name = trim($request->input('name'));       
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('substratecategories.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = SubstrateCategory::findOrFail($id);
            $params = [
                'title' => 'Delete Substrate Category',
                'dataObj' => $dataObj,
            ];
            return view('general.substratecategory.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = SubstrateCategory::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = SubstrateCategory::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('substratecategories.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'name' => $request['name'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('general/substratecategory/list', ['dataLst' => $dataLst, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = SubstrateCategory::where('deleted_at', '=', null)->orderBy('id', 'DESC');
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
