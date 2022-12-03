<?php

namespace Larashop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Larashop\Http\Controllers\Controller;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;

use Larashop\Models\General\Location;
use Larashop\Models\General\Company;

class LocationsController extends Controller
{
    public function __construct(){
        $this->middleware('permission:locations_access_allow');
        $this->middleware('permission:locations_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:locations_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:locations_delete', ['only' => ['show', 'delete']]);
    }  

    public function index(){ 
        $dataLst = Location::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();

        $dataCompanies = Company::where('deleted_at', '=', null)->get();

        $params = [
            'title' => 'Locations List',
            'dataLst' => $dataLst,
            'dataCompanies' => $dataCompanies,           
        ];

        return view('admin.locations.list')->with($params);
    }

    public function create(){  
        $dataCompanies = Company::where('deleted_at', '=', null)->get();             
        $params = [
            'title' => 'Create Location', 
            'dataCompanies' => $dataCompanies, 
        ];
        return view('admin.locations.create')->with($params);
    }

    
    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:locations',
            'companies_id' => 'required',             
        ]);

        $dataObjIns = Location::create([
            'code' => trim($request->input('code')),
            'name' => trim($request->input('name')),
            'companies_id' => trim($request->input('companies_id')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('locations.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }
   
    public function edit($id) {               
        $dataObj = Location::findOrFail($id);
        $dataCompanies = Company::where('deleted_at', '=', null)->get();
        $params = [
            'title' => 'Edit Location',
            'dataObj' => $dataObj,
            'dataCompanies' => $dataCompanies,
        ];
        return view('admin.locations.edit')->with($params);
    }
   
    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:locations,name,'.$id,
            'companies_id' => 'required',
        ]);

        $dataObjUpd = Location::findOrFail($id);
        $dataObjUpd->code = trim($request->input('code'));
        $dataObjUpd->name = trim($request->input('name'));  
        $dataObjUpd->companies_id = $request->input('companies_id');      
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('locations.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = Location::findOrFail($id);
            $params = [
                'title' => 'Delete Location',
                'dataObj' => $dataObj,
            ];
            return view('admin.locations.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }
  
    public function destroy($id){

        $dataObjUpd = Location::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = Location::findOrFail($id);
        $dataObjDel->delete();
        
        return redirect()->route('locations.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [
            'code' => $request['code'],
            'name' => $request['name'],   
            'companies_id' => $request['companies_id'],                   
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        $dataCompanies = Company::where('deleted_at', '=', null)->get();
        return view('admin/locations/list', ['dataLst' => $dataLst, 'dataCompanies' => $dataCompanies, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = Location::where('deleted_at', '=', null)->orderBy('id', 'DESC');
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

                if ($fields[$index] == 'companies_id') {
                    if ($constraint != null) { 
                        $query = $query->where('companies_id', '=', $constraint);                       
                    } 
                }            
            $index++;
        }       
        return $query->paginate(100000);
    }

    public function getLocationList($company_id) {          
        $dataObj = Location::where('companies_id', '=', $company_id)->get(); 
        return Response::json($dataObj); 
    }

}
