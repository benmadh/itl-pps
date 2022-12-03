<?php

namespace Larashop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;

use Larashop\Models\General\Company;

class CompaniesController extends Controller
{
    public function __construct(){
        $this->middleware('permission:companies_access_allow');
        $this->middleware('permission:companies_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:companies_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:companies_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = Company::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
                            
        $params = [
            'title' => 'Companies List',
            'dataLst' => $dataLst,           
        ];

        return view('admin.companies.list')->with($params);
    }

    public function create(){               
        $params = [
            'title' => 'Create Company', 
        ];
        return view('admin.companies.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:companies',                  
        ]);

        $dataObjIns = Company::create([
            'code' => trim($request->input('code')),
            'name' => trim($request->input('name')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('companies.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = Company::findOrFail($id);
        $params = [
            'title' => 'Edit Company',
            'dataObj' => $dataObj,
        ];
        return view('admin.companies.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:companies,name,'.$id,            
        ]);

        $dataObjUpd = Company::findOrFail($id);
        $dataObjUpd->code = trim($request->input('code'));
        $dataObjUpd->name = trim($request->input('name'));       
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('companies.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = Company::findOrFail($id);
            $params = [
                'title' => 'Delete Company',
                'dataObj' => $dataObj,
            ];
            return view('admin.companies.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = Company::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = Company::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('companies.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [
            'code' => $request['code'],
            'name' => $request['name'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('admin/companies/list', ['dataLst' => $dataLst, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = Company::where('deleted_at', '=', null)->orderBy('id', 'DESC');
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
