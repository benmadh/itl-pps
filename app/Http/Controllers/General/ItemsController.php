<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Larashop\Models\General\Item;
use Larashop\Models\General\Unit;
use Larashop\Models\General\ItemGroup;
use Larashop\Models\General\SubstrateCategory;

class ItemsController extends Controller
{
    public function __construct(){
        $this->middleware('permission:items_access_allow');
        $this->middleware('permission:items_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:items_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:items_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $unitsData = Unit::where('deleted_at', '=', null)->get();
        $itemGroupData = ItemGroup::where('deleted_at', '=', null)->get();
        $substrateCatData = SubstrateCategory::where('deleted_at', '=', null)->get();

        $dataLst = Item::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
                            
        $params = [
            'title' => 'Items List',
            'dataLst' => $dataLst,  
            'unitsData' => $unitsData, 
            'itemGroupData' => $itemGroupData,  
            'substrateCatData' => $substrateCatData,         
        ];

        return view('general.items.list')->with($params);
    }

    public function create(){ 
        $unitsData = Unit::where('deleted_at', '=', null)->get();
        $itemGroupData = ItemGroup::where('deleted_at', '=', null)->get();
        $substrateCatData = SubstrateCategory::where('deleted_at', '=', null)->get();              
        $params = [
            'title' => 'Create Items', 
            'unitsData' => $unitsData, 
            'itemGroupData' => $itemGroupData,  
            'substrateCatData' => $substrateCatData,
        ];
        return view('general.items.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'code' => 'required|unique:items',
            'name' => 'required',    
            'unit_id' => 'required',
            'item_group_id' => 'required',
            'substrate_category_id' => 'required',                           
        ]);

        $dataObjIns = Item::create([
            'code' => trim($request->input('code')),
            'name' => trim($request->input('name')),
            'unit_id' => $request->input('unit_id'),
            'item_group_id' => $request->input('item_group_id'),
            'substrate_category_id' => $request->input('substrate_category_id'),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('items.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = Item::findOrFail($id);
        $unitsData = Unit::where('deleted_at', '=', null)->get();
        $itemGroupData = ItemGroup::where('deleted_at', '=', null)->get();
        $substrateCatData = SubstrateCategory::where('deleted_at', '=', null)->get();      
        $params = [
            'title' => 'Edit Items',
            'dataObj' => $dataObj,
            'unitsData' => $unitsData, 
            'itemGroupData' => $itemGroupData,  
            'substrateCatData' => $substrateCatData,
        ];
        return view('general.items.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'code' => 'required|unique:items,code,'.$id,
            'name' => 'required', 
            'unit_id' => 'required',
            'item_group_id' => 'required',
            'substrate_category_id' => 'required', 
        ]);

        if ($request->hasFile('picture')) {
          $file = array('picture' => $request->file('picture'));
          $destinationPath = 'upload/images/items/'; // upload path
          $extension = $request->file('picture')->getClientOriginalExtension(); 
          $fileName = $request->input('code').'.'.Str::lower($extension); // renaming image
          $request->file('picture')->move($destinationPath, $fileName);         
        }else{
          $fileName = null;         
        }

        $dataObjUpd = Item::findOrFail($id);        
        $dataObjUpd->code = trim($request->input('code'));  
        $dataObjUpd->name = trim($request->input('name'));
        $dataObjUpd->unit_id = $request->input('unit_id'); 
        $dataObjUpd->item_group_id = $request->input('item_group_id'); 
        $dataObjUpd->substrate_category_id = $request->input('substrate_category_id');  
        if(!$fileName == null){
            $dataObjUpd->photo= $fileName;                  
        }      
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('items.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = Item::findOrFail($id);
            $params = [
                'title' => 'Delete Items',
                'dataObj' => $dataObj,
            ];
            return view('general.items.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = Item::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = Item::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('items.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'code' => $request['code'],
            'name' => $request['name'], 
            'unit_id' => $request['unit_id'], 
            'item_group_id' => $request['item_group_id'], 
            'substrate_category_id' => $request['substrate_category_id'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        $unitsData = Unit::where('deleted_at', '=', null)->get();
        $itemGroupData = ItemGroup::where('deleted_at', '=', null)->get();
        $substrateCatData = SubstrateCategory::where('deleted_at', '=', null)->get();    
        return view('general/items/list', ['dataLst' => $dataLst, 
                                            'unitsData' => $unitsData, 
                                            'itemGroupData' => $itemGroupData,  
                                            'substrateCatData' => $substrateCatData,
                                            'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = Item::where('deleted_at', '=', null)->orderBy('id', 'DESC');
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
            if($fields[$index] == 'unit_id'){
                if ($constraint != null) { 
                    $query = $query->where('unit_id', '=', $constraint); 
                }
            } 
            if($fields[$index] == 'item_group_id'){
                if ($constraint != null) { 
                    $query = $query->where('item_group_id', '=', $constraint); 
                }
            } 
            if($fields[$index] == 'substrate_category_id'){
                if ($constraint != null) { 
                    $query = $query->where('substrate_category_id', '=', $constraint); 
                }
            } 
            $index++;
        }       
        return $query->paginate(100000);
    }

    public function getItemDetails($item_id){
            
        $data = DB::table('items')
                    ->leftJoin('units', 'items.unit_id', '=', 'units.id')
                    ->select('items.*', 'units.name as unit_name')
                    ->where('items.id', '=', $item_id)
                    ->get();
       
        return Response::json($data);       
    }
}
