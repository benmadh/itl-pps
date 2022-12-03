<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;

use Larashop\Models\General\ItemGroup;

class ItemGroupsController extends Controller
{
    public function __construct(){
        $this->middleware('permission:item_groups_access_allow');
        $this->middleware('permission:item_groups_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:item_groups_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:item_groups_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = ItemGroup::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
                            
        $params = [
            'title' => 'Item Group List',
            'dataLst' => $dataLst,           
        ];

        return view('general.itemgroups.list')->with($params);
    }

    public function create(){               
        $params = [
            'title' => 'Create Item Group', 
        ];
        return view('general.itemgroups.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:item_groups',                   
        ]);

        $dataObjIns = ItemGroup::create([
            'name' => trim($request->input('name')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('itemgroups.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = ItemGroup::findOrFail($id);
        $params = [
            'title' => 'Edit Item Group',
            'dataObj' => $dataObj,
        ];
        return view('general.itemgroups.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:item_groups,name,'.$id,
        ]);

        $dataObjUpd = ItemGroup::findOrFail($id);        
        $dataObjUpd->name = trim($request->input('name'));       
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('itemgroups.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = ItemGroup::findOrFail($id);
            $params = [
                'title' => 'Delete Item Group',
                'dataObj' => $dataObj,
            ];
            return view('general.itemgroups.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = ItemGroup::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = ItemGroup::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('itemgroups.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'name' => $request['name'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('general/itemgroups/list', ['dataLst' => $dataLst, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = ItemGroup::where('deleted_at', '=', null)->orderBy('id', 'DESC');
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
