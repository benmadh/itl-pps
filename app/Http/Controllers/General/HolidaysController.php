<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Response;
use Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;

use Illuminate\Support\Facades\DB;
use Larashop\Models\General\DayType;
use Larashop\Models\General\Company;
use Larashop\Models\General\Holiday;

class HolidaysController extends Controller
{
    public function __construct(){
        $this->middleware('permission:holiday_access_allow');
        $this->middleware('permission:holiday_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:holiday_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:holiday_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = Holiday::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
       
        $dataCompanies = Company::where('deleted_at', '=', null)->get();
        $dayTypes = DayType::where('deleted_at', '=', null)->get();
        $date = Carbon::now()->toDateString();                  
        $params = [
            'title' => 'Day Type List',
            'dataLst' => $dataLst, 
            'dataCompanies' =>  $dataCompanies,  
            'dayTypes' =>  $dayTypes, 
            'date' => $date,        
        ];

        return view('general.holidays.list')->with($params);
    }

    public function create(){  
        $dataCompanies = Company::where('deleted_at', '=', null)->get();
        $dayTypes = DayType::where('deleted_at', '=', null)->get();
        $date = Carbon::now()->toDateString();                  
        $params = [
            'title' => 'Create Holiday', 
            'dataCompanies' =>  $dataCompanies, 
            'dayTypes' =>  $dayTypes, 
            'date' => $date, 
        ];
        return view('general.holidays.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'date' => 'required|date',
            'companies_id' => 'required',  
            'day_types_id' => 'required',
        ]);

        $dataObjIns = Holiday::create([
            'date' => $request->input('date'),
            'companies_id' => trim($request->input('companies_id')),
            'day_types_id' => trim($request->input('day_types_id')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('holidays.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = Holiday::findOrFail($id);
        $dataCompanies = Company::where('deleted_at', '=', null)->get();
        $dayTypes = DayType::where('deleted_at', '=', null)->get();
        $params = [
            'title' => 'Edit Holiday',
            'dataObj' => $dataObj,
            'dataCompanies' =>  $dataCompanies,
            'dayTypes' =>  $dayTypes,  
        ];
        return view('general.holidays.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'date' => 'required|date',
            'companies_id' => 'required',  
            'day_types_id' => 'required',
        ]);

        $dataObjUpd = Holiday::findOrFail($id);        
        $dataObjUpd->date = $request->input('date');
        $dataObjUpd->companies_id = trim($request->input('companies_id'));  
        $dataObjUpd->day_types_id = trim($request->input('day_types_id'));         
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('holidays.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = Holiday::findOrFail($id);
            $params = [
                'title' => 'Delete Holiday',
                'dataObj' => $dataObj,
            ];
            return view('general.holidays.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = Holiday::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = Holiday::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('holidays.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'date' => $request['date'], 
            'companies_id' => $request['companies_id'], 
            'day_types_id' => $request['day_types_id'],                       
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        $dataCompanies = Company::where('deleted_at', '=', null)->get();
        $dayTypes = DayType::where('deleted_at', '=', null)->get();
        $date = $request['date']; 
        return view('general/holidays/list', ['dataLst' => $dataLst, 'date' => $date, 'dayTypes' => $dayTypes,'dataCompanies' => $dataCompanies, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = Holiday::where('deleted_at', '=', null)->orderBy('id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {                
               
                if ($fields[$index] == 'date') {
                    if ($constraint != null) {                        
                        $query = $query->where('date', '=', $constraint); 
                    } 
                } 

                if ($fields[$index] == 'day_types_id') {
                    if ($constraint != null) {                        
                        $query = $query->where('day_types_id', '=', $constraint); 
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
}
