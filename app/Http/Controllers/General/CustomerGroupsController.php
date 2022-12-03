<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;

use Larashop\Models\General\CustomerGroup;


class CustomerGroupsController extends Controller
{
    public function __construct(){
        $this->middleware('permission:customer_groups_access_allow');
        $this->middleware('permission:customer_groups_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:customer_groups_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customer_groups_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = CustomerGroup::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
                            
        $params = [
            'title' => 'Customer Ggroups List',
            'dataLst' => $dataLst,           
        ];

        return view('general.customergroups.list')->with($params);
    }

    public function create(){               
        $params = [
            'title' => 'Create Customer Ggroup', 
        ];
        return view('general.customergroups.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:customer_groups',
            'code' => 'required',                   
        ]);

        $dataObjIns = CustomerGroup::create([
            'code' => trim($request->input('code')),
            'name' => trim($request->input('name')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('customergroups.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = CustomerGroup::findOrFail($id);
        $params = [
            'title' => 'Edit Customer Ggroup',
            'dataObj' => $dataObj,
        ];
        return view('general.customergroups.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:customer_groups,name,'.$id,
            'code' => 'required', 
        ]);

        $dataObjUpd = CustomerGroup::findOrFail($id);        
        $dataObjUpd->code = trim($request->input('code'));  
        $dataObjUpd->name = trim($request->input('name'));       
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('customergroups.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = CustomerGroup::findOrFail($id);
            $params = [
                'title' => 'Delete Customer Ggroup',
                'dataObj' => $dataObj,
            ];
            return view('general.customergroups.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = CustomerGroup::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = CustomerGroup::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('customergroups.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'code' => $request['code'],
            'name' => $request['name'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('general/customergroups/list', ['dataLst' => $dataLst, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = CustomerGroup::where('deleted_at', '=', null)->orderBy('id', 'DESC');
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
