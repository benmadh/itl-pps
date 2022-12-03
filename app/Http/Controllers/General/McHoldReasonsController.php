<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;

use Larashop\Models\General\McHoldReason;

class McHoldReasonsController extends Controller
{
    public function __construct(){
        $this->middleware('permission:mc_hold_reason_access_allow');
        $this->middleware('permission:mc_hold_reason_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:mc_hold_reason_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:mc_hold_reason_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $dataLst = McHoldReason::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
                            
        $params = [
            'title' => 'Machine Hold Reason List',
            'dataLst' => $dataLst,           
        ];

        return view('general.mcholdreasons.list')->with($params);
    }

    public function create(){               
        $params = [
            'title' => 'Create Machine Hold Reason', 
        ];
        return view('general.mcholdreasons.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:mc_hold_reasons',                   
        ]);

        $dataObjIns = McHoldReason::create([
            'name' => trim($request->input('name')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('mcholdreasons.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = McHoldReason::findOrFail($id);
        $params = [
            'title' => 'Edit Machine Hold Reason',
            'dataObj' => $dataObj,
        ];
        return view('general.mcholdreasons.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:mc_hold_reasons,name,'.$id,
        ]);

        $dataObjUpd = McHoldReason::findOrFail($id);        
        $dataObjUpd->name = trim($request->input('name'));       
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('mcholdreasons.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = McHoldReason::findOrFail($id);
            $params = [
                'title' => 'Delete Machine Hold Reason',
                'dataObj' => $dataObj,
            ];
            return view('general.mcholdreasons.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = McHoldReason::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = McHoldReason::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('mcholdreasons.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'name' => $request['name'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('general/mcholdreasons/list', ['dataLst' => $dataLst, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = McHoldReason::where('deleted_at', '=', null)->orderBy('id', 'DESC');
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
