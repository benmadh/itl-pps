<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Larashop\Models\General\Customer;
use Larashop\Models\General\CustomerGroup;
use Larashop\Models\General\Company;

class CustomersController extends Controller
{
    public function __construct(){
        // $this->middleware('permission:customers_access_allow');
        // $this->middleware('permission:customers_create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:customers_edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:customers_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = Customer::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
                            
        $dataCusGrp = CustomerGroup::where('deleted_at', '=', null)->get();
        $dataCompany = Company::where('deleted_at', '=', null)->get();
        $params = [
            'title' => 'Customer List',
            'dataLst' => $dataLst,  
            'dataCusGrp' => $dataCusGrp,
            'dataCompany' => $dataCompany,
        ];

        return view('general.customers.list')->with($params);
    }

    public function create(){  
        $dataCusGrp = CustomerGroup::where('deleted_at', '=', null)->get();
        $dataCompany = Company::where('deleted_at', '=', null)->get();
        $params = [
            'title' => 'Create Customer', 
            'dataCusGrp' => $dataCusGrp,
            'dataCompany' => $dataCompany,
        ];
        return view('general.customers.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'account_code' => 'required|unique:customers', 
            'name' => 'required', 
            'customer_group_id' => 'required',  
            'company_id' => 'required',             
        ]);
        
        $dataObjIns = Customer::create([
            'account_code' => trim($request->input('account_code')),
            'name' => trim($request->input('name')),
            'customer_group_id' => $request->input('customer_group_id'),
            'company_id' => $request->input('company_id'),
            'adress_1' => trim($request->input('adress_1')),
            'adress_2' => trim($request->input('adress_2')),  
            'city' => trim($request->input('city')),
            'telephone_land' => trim($request->input('telephone_land')),
            'telephone_fax' => trim($request->input('telephone_fax')),
            'official_email' => $request->input('official_email'),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('customers.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = Customer::findOrFail($id);
        $dataCusGrp = CustomerGroup::where('deleted_at', '=', null)->get(); 
        $dataCompany = Company::where('deleted_at', '=', null)->get();      
        $params = [
            'title' => 'Edit Customer',
            'dataObj' => $dataObj,
            'dataCusGrp' => $dataCusGrp, 
            'dataCompany' => $dataCompany,
        ];
        return view('general.customers.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'account_code' => 'required|unique:customers,account_code,'.$id,
            'name' => 'required', 
            'customer_group_id' => 'required', 
            'company_id' => 'required', 
        ]);
       
        $dataObjUpd = Customer::findOrFail($id);        
        $dataObjUpd->account_code = trim($request->input('account_code'));
        $dataObjUpd->name = trim($request->input('name'));  
        $dataObjUpd->customer_group_id = $request->input('customer_group_id'); 
        $dataObjUpd->company_id = $request->input('company_id');       
        $dataObjUpd->adress_1 = $request->input('adress_1'); 
        $dataObjUpd->adress_2 = $request->input('adress_2'); 
        $dataObjUpd->city = $request->input('city');  
        $dataObjUpd->telephone_land = $request->input('telephone_land');
        $dataObjUpd->telephone_fax = $request->input('telephone_fax');
        $dataObjUpd->official_email = $request->input('official_email');   
        $dataObjUpd->updated_by =  Auth::user()->id;       
        $dataObjUpd->save();
       
        return redirect()->route('customers.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = Customer::findOrFail($id);
            $params = [
                'title' => 'Delete Customer',
                'dataObj' => $dataObj,
            ];
            return view('general.customers.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = Customer::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = Customer::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('customers.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'account_code' => $request['account_code'], 
            'name' => $request['name'],
            'customer_group_id' => $request['customer_group_id'],
            'company_id' => $request['company_id'],
        ];
        $dataLst = $this->doSearchingQuery($constraints);
        $dataCusGrp = CustomerGroup::where('deleted_at', '=', null)->get();
        $dataCompany = Company::where('deleted_at', '=', null)->get();         

        return view('general/customers/list', ['dataLst' => $dataLst, 
                                                'dataCusGrp' => $dataCusGrp, 
                                                'dataCompany' => $dataCompany,
                                                'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = Customer::where('deleted_at', '=', null)->orderBy('id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {                
                if ($fields[$index] == 'account_code') {
                    if ($constraint != null) { 
                        $query = $query->where('account_code', 'like', '%'.trim($constraint).'%');
                    } 
                }  
                if ($fields[$index] == 'name') {
                    if ($constraint != null) { 
                        $query = $query->where('name', 'like', '%'.trim($constraint).'%');
                    } 
                } 
                if($fields[$index] == 'customer_group_id'){
                    if ($constraint != null) { 
                        $query = $query->where('customer_group_id', '=', $constraint); 
                    }
                }  
                if($fields[$index] == 'company_id'){
                    if ($constraint != null) { 
                        $query = $query->where('company_id', '=', $constraint); 
                    }
                }                
            $index++;
        }       
        return $query->paginate(100000);
    }
}
