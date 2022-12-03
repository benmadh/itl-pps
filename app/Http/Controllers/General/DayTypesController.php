<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Larashop\Models\General\DayType;
use Larashop\Models\General\Company;

class DayTypesController extends Controller
{
    public function __construct(){
        $this->middleware('permission:day_type_access_allow');
        $this->middleware('permission:day_type_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:day_type_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:day_type_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = DayType::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();

        $dataCompanies = Company::where('deleted_at', '=', null)->get();                            
        $params = [
            'title' => 'Day Type List',
            'dataLst' => $dataLst, 
            'dataCompanies' =>  $dataCompanies,          
        ];

        return view('general.daytypes.list')->with($params);
    }

    public function create(){  
        $dataCompanies = Company::where('deleted_at', '=', null)->get();            
        $params = [
            'title' => 'Create Day Type', 
            'dataCompanies' =>  $dataCompanies, 
        ];
        return view('general.daytypes.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:day_types,name,'.$request->input('name'), 
            'companies_id' => 'required',         
            'colorpicker_id' => 'required|unique:day_types,colorpicker_id,'.$request->input('colorpicker_id'),                 
        ]);

        $dataObjIns = DayType::create([
            'name' => trim($request->input('name')),
            'colorpicker_id' => trim($request->input('colorpicker_id')),
            'companies_id' => trim($request->input('companies_id')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('daytypes.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = DayType::findOrFail($id);
        $dataCompanies = Company::where('deleted_at', '=', null)->get();
        $params = [
            'title' => 'Edit Day Type',
            'dataObj' => $dataObj,
            'dataCompanies' =>  $dataCompanies, 
        ];
        return view('general.daytypes.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:day_types,name,'.$id,
            'colorpicker_id' => 'required',
            'companies_id' => 'required',
        ]);

        $dataObjUpd = DayType::findOrFail($id);        
        $dataObjUpd->name = trim($request->input('name'));
        $dataObjUpd->colorpicker_id = trim($request->input('colorpicker_id'));  
        $dataObjUpd->companies_id = trim($request->input('companies_id'));         
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('daytypes.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = DayType::findOrFail($id);
            $params = [
                'title' => 'Delete Day Type',
                'dataObj' => $dataObj,
            ];
            return view('general.daytypes.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = DayType::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = DayType::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('daytypes.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'name' => $request['name'], 
            'companies_id' => $request['companies_id'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        $dataCompanies = Company::where('deleted_at', '=', null)->get();
        return view('general/daytypes/list', ['dataLst' => $dataLst, 'dataCompanies' => $dataCompanies, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = DayType::where('deleted_at', '=', null)->orderBy('id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {                
                if ($fields[$index] == 'name') {
                    if ($constraint != null) { 
                        $query = $query->where('name', 'like', '%'.trim($constraint).'%');
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

    public function getDayTypeList($companies_id) {          
        $dataObj = DayType::where('companies_id', '=', $companies_id)->get(); 
        return Response::json($dataObj); 
    }
}
