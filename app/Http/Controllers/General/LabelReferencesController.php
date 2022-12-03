<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Larashop\Models\General\LabelReference;
use Larashop\Models\General\Chain;
use Larashop\Models\General\Department;
use Larashop\Models\General\LabelType;
use Larashop\Models\General\Item;
use Larashop\Models\General\ItemLabelReference;

class LabelReferencesController extends Controller
{
    public $arr_dep = array('12','13','14','15','16','17','18');
    public function __construct(){
        $this->middleware('permission:label_reference_access_allow');
        $this->middleware('permission:label_reference_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:label_reference_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:label_reference_delete', ['only' => ['show', 'delete']]);
    }

    public function index(){ 
        $chainsData = Chain::where('deleted_at', '=', null)->get();
        $labelTypeData = LabelType::where('deleted_at', '=', null)->get();
        $deptData = Department::where('deleted_at', '=', null)
                     ->whereIn('id', $this->arr_dep)
                     ->get(); 

        $dataLst = LabelReference::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();
                            
        $params = [
            'title' => 'Label Referance List',
            'dataLst' => $dataLst,  
            'chainsData' => $chainsData, 
            'deptData' => $deptData,  
            'labelTypeData' => $labelTypeData,         
        ];

        return view('general.labelreferences.list')->with($params);
    }

    public function create(){ 
        $chainsData = Chain::where('deleted_at', '=', null)->get();
        $deptData = Department::where('deleted_at', '=', null)
                     ->whereIn('id', $this->arr_dep)
                     ->get();
        
        $labelTypeData = LabelType::where('deleted_at', '=', null)->get();              
        $params = [
            'title' => 'Create Label Referance', 
            'chainsData' => $chainsData, 
            'deptData' => $deptData,  
            'labelTypeData' => $labelTypeData,
        ];
        return view('general.labelreferences.create')->with($params);
    }

    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:label_references',
            'ground_colour' => 'required', 
            'print_colour' => 'required',   
            'chain_id' => 'required',
            'labeltype_id' => 'required',
            'department_id' => 'required', 
            'default_lenght' => 'integer', 
            'default_width' => 'integer',                           
        ]);

        $dataObjIns = LabelReference::create([
            'name' => trim($request->input('name')),
            'description' => trim($request->input('description')),
            'ground_colour' => trim($request->input('ground_colour')),
            'print_colour' => trim($request->input('print_colour')),
            'combo' => trim($request->input('combo')),
            'default_lenght' => $request->input('default_lenght'),
            'default_width' => $request->input('default_width'),
            'chain_id' => $request->input('chain_id'),
            'labeltype_id' => $request->input('labeltype_id'),
            'department_id' => $request->input('department_id'),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('labelreferences.index')->with('success', trans('general.form.flash.created',['name' => $dataObjIns->name]));
    }

    public function edit($id) {               
        $dataObj = LabelReference::findOrFail($id);
        $chainsData = Chain::where('deleted_at', '=', null)->get();
        $deptData = Department::where('deleted_at', '=', null)
                     ->whereIn('id', $this->arr_dep)
                     ->get();
        $labelTypeData = LabelType::where('deleted_at', '=', null)->get();      
        $params = [
            'title' => 'Edit Label Referance',
            'dataObj' => $dataObj,
            'chainsData' => $chainsData, 
            'deptData' => $deptData,  
            'labelTypeData' => $labelTypeData,
        ];
        return view('general.labelreferences.edit')->with($params);
    }

    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:label_references,name,'.$id,
            'ground_colour' => 'required', 
            'print_colour' => 'required',   
            'chain_id' => 'required',
            'labeltype_id' => 'required',
            'department_id' => 'required', 
            'default_lenght' => 'integer', 
            'default_width' => 'integer',     
        ]);

        if ($request->hasFile('picture')) {
          $file = array('picture' => $request->file('picture'));
          $destinationPath = 'upload/images/labelreferences/'; // upload path
          $extension = $request->file('picture')->getClientOriginalExtension(); 
          $fileName = $request->input('name').'.'.Str::lower($extension); // renaming image
          $request->file('picture')->move($destinationPath, $fileName);         
        }else{
          $fileName = null;         
        }

        $dataObjUpd = LabelReference::findOrFail($id);        
        $dataObjUpd->name = trim($request->input('name'));  
        $dataObjUpd->description = trim($request->input('description'));
        $dataObjUpd->ground_colour = trim($request->input('ground_colour'));
        $dataObjUpd->print_colour = trim($request->input('print_colour'));
        $dataObjUpd->combo = trim($request->input('combo'));
        $dataObjUpd->default_lenght = $request->input('default_lenght'); 
        $dataObjUpd->default_width = $request->input('default_width'); 
        $dataObjUpd->chain_id = $request->input('chain_id'); 
        $dataObjUpd->labeltype_id = $request->input('labeltype_id'); 
        $dataObjUpd->department_id = $request->input('department_id');  
        if(!$fileName == null){
            $dataObjUpd->file_name= $fileName;                  
        }      
        $dataObjUpd->updated_by =  Auth::user()->id;
        $dataObjUpd->save();

        return redirect()->route('labelreferences.index')->with('success', trans('general.form.flash.updated',['name' => $dataObjUpd->name]));
    }

    public function show($id){
        try{
            $dataObj = LabelReference::findOrFail($id);
            $params = [
                'title' => 'Delete Label Referance',
                'dataObj' => $dataObj,
            ];
            return view('general.labelreferences.delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function destroy($id){

        $dataObjUpd = LabelReference::findOrFail($id);       
        $dataObjUpd->deleted_by =  Auth::user()->id;
        $dataObjUpd->save();

        $dataObjDel = LabelReference::findOrFail($id);
        $dataObjDel->delete();

        return redirect()->route('labelreferences.index')->with('success', trans('general.form.flash.deleted',['name' => $dataObjDel->name]));
    }

    public function search(Request $request){
        $constraints = [            
            'name' => $request['name'],
            'description' => $request['description'], 
            'chain_id' => $request['chain_id'], 
            'labeltype_id' => $request['labeltype_id'], 
            'department_id' => $request['department_id'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        $chainsData = Chain::where('deleted_at', '=', null)->get();
        $deptData = Department::where('deleted_at', '=', null)
                     ->whereIn('id', $this->arr_dep)
                     ->get();
        $labelTypeData = LabelType::where('deleted_at', '=', null)->get();        
        return view('general/labelreferences/list', ['dataLst' => $dataLst, 
                                            'chainsData' => $chainsData, 
                                            'deptData' => $deptData,  
                                            'labelTypeData' => $labelTypeData,
                                            'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = LabelReference::where('deleted_at', '=', null)->orderBy('id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {                
            if ($fields[$index] == 'name') {
                if ($constraint != null) { 
                    $query = $query->where('name', 'like', '%'.trim($constraint).'%');
                } 
            }  
            if ($fields[$index] == 'description') {
                if ($constraint != null) { 
                    $query = $query->where('description', 'like', '%'.trim($constraint).'%');
                } 
            }
            if($fields[$index] == 'chain_id'){
                if ($constraint != null) { 
                    $query = $query->where('chain_id', '=', $constraint); 
                }
            } 
            if($fields[$index] == 'labeltype_id'){
                if ($constraint != null) { 
                    $query = $query->where('labeltype_id', '=', $constraint); 
                }
            } 
            if($fields[$index] == 'department_id'){
                if ($constraint != null) { 
                    $query = $query->where('department_id', '=', $constraint); 
                }
            } 
            $index++;
        }       
        return $query->paginate(100000);
    }

    public function edit_Item_references($id){
        try{
            $refObj = LabelReference::findOrFail($id);
            $items = Item::all();
            $params = [
                'title' => 'Allocate Items',
                'refObj' => $refObj,
                'items' => $items,
            ];

            return view('general.labelreferences.item_references')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

     public function update_Item_references(Request $request){
          
        $id = $request->id;
        $item_labelreferences = ItemLabelReference::where('deleted_at', '=', null)
                 ->where('label_reference_id','=',$id)                                                        
                 ->get();


        foreach ($request->item_id as $key => $v) {  

            if (array_key_exists($key,$request->item_labelreferences_id)){
                  $did = $request->item_labelreferences_id[$key];
                  if ($did != null){
                        $data = array('item_id'=> $request->item_id[$key],                  
                              'unit_price'=> $request->unit_price[$key],
                              'quantity'=> $request->quantity[$key], 
                              'unit_value'=> $request->item_value[$key],
                              'label_reference_id'=> $id,
                              'updated_at'=> date('Y-m-d H:i:s'),                   
                              'updated_by'=> Auth::user()->id);
                    ItemLabelReference::where('id', $did)
                                        ->update($data);
                  }else{
                    $data = array('item_id'=> $request->item_id[$key],                  
                          'unit_price'=> $request->unit_price[$key],
                          'quantity'=> $request->quantity[$key], 
                          'unit_value'=> $request->item_value[$key],
                          'label_reference_id'=> $id,
                          'created_at'=> date('Y-m-d H:i:s'),                   
                          'created_by'=> Auth::user()->id);

                    ItemLabelReference::insert($data);
                  }      
            }else{
               $data = array('item_id'=> $request->item_id[$key],                  
                  'unit_price'=> $request->unit_price[$key],
                  'quantity'=> $request->quantity[$key], 
                  'unit_value'=> $request->item_value[$key],
                  'label_reference_id'=> $id,
                  'created_at'=> date('Y-m-d H:i:s'),                   
                  'created_by'=> Auth::user()->id);  
                ItemLabelReference::insert($data);
            }         

        } 

        foreach ($item_labelreferences as $row => $v2) {
            $item_labelreferences_id = $v2->id;         
            $delete = true;
            foreach ($request->item_labelreferences_id as $key => $v) {
              $did = $request->item_labelreferences_id[$key]; 
              if ($did==$item_labelreferences_id){
                $delete = false;
              } 
            }
            if($delete){                     
              $item_labelreferences = ItemLabelReference::where('id',$item_labelreferences_id)->first();
              $item_labelreferences->delete();  
            }
        }

        return redirect()->route('labelreferences.index')->with('success', trans('general.form.flash.updated',['name' => $request->name]));   
    }
}
