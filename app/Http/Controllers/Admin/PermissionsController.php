<?php

namespace Larashop\Http\Controllers\Admin;

use Larashop\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Larashop\Http\Controllers\Controller;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;

class PermissionsController extends Controller
{
    
  /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $this->middleware('permission:permissions_access_allow');
    }
    /**
     * Display a listing of Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){ 
        $dataLst = Permission::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();

        $params = [
            'title' => 'Permissions Listing',
            'dataLst' => $dataLst,           
        ];

        return view('admin.permissions.permissions_list')->with($params);
    }

    
    /**
     * Show the form for creating new Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){               
        $params = [
            'title' => 'Create Permissions', 
        ];
        return view('admin.permissions.permissions_create')->with($params);
    }

    /**
     * Store a newly created Permission in storage.
     *
     * @param  \App\Http\Requests\StorePermissionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
       
        $this->validate($request, [
            'name' => 'required|unique:permissions',
            'display_name' => 'required',
            'description' => 'required',
        ]);

        $permissions = Permission::create([
            'name' => trim($request->input('name')),
            'display_name' => trim($request->input('display_name')),
            'description' => trim($request->input('description')),
            'created_by'=>Auth::user()->id,
        ]);
        return redirect()->route('permissions.index')->with('success', trans('general.form.flash.created',['name' => $permissions->name]));
    }


    /**
     * Show the form for editing Permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {               
        $permissions = Permission::findOrFail($id);
        $params = [
            'title' => 'Edit Permissions',
            'permissions' => $permissions,
        ];
        return view('admin.permissions.permissions_edit')->with($params);
    }

    /**
     * Update Permission in storage.
     *
     * @param  \App\Http\Requests\UpdatePermissionsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
               
        $this->validate($request, [
            'name' => 'required|unique:permissions,name,'.$id,
            'display_name' => 'required',
            'description' => 'required',
        ]);

        $permissions = Permission::findOrFail($id);
        $permissions->name = trim($request->input('name'));
        $permissions->display_name = trim($request->input('display_name'));
        $permissions->description = trim($request->input('description'));
        $permissions->updated_by =  Auth::user()->id;
        $permissions->save();

        return redirect()->route('permissions.index')->with('success', trans('general.form.flash.updated',['name' => $permissions->name]));
    }

    public function show($id){
        try{
            $permissions = Permission::findOrFail($id);
            $params = [
                'title' => 'Delete Permission',
                'permissions' => $permissions,
            ];
            return view('admin.permissions.permissions_delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $delData = Permission::findOrFail($id);
        $delData->delete();
        return redirect()->route('permissions.index')->with('success', trans('general.form.flash.deleted',['name' => $delData->name]));
    }

    public function search(Request $request){
        $constraints = [
            'name' => $request['name'],
            'display_name' => $request['display_name'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('admin/permissions/permissions_list', ['dataLst' => $dataLst, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints){       
        $query = Permission::where('deleted_at', '=', null)->orderBy('id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {                
               if ($fields[$index] == 'name') {
                    if ($constraint != null) { 
                        $query = $query->where('name', 'like', '%'.trim($constraint).'%');
                    } 
                }                            
                if ($fields[$index] == 'display_name') {
                    if ($constraint != null) { 
                        $query = $query->where('display_name', 'like', '%'.trim($constraint).'%');                       
                    } 
                }            
            $index++;
        }       
        return $query->paginate(100000);
    }

}
