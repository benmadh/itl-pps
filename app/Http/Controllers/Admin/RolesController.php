<?php

namespace Larashop\Http\Controllers\Admin;

use Larashop\Models\Role;
use Larashop\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Larashop\Http\Controllers\Controller;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    

    /**
     * Instantiate a new UserController instance.
     */
    public function __construct(){
        $this->middleware('permission:roles_access_allow');
    }
    /**
     * Display a listing of Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $dataLst = Role::where('deleted_at', '=', null)
                             ->orderBy('id', 'DESC')  
                             ->limit(10)                                   
                             ->get();       
        $params = [
            'title' => 'Role Listing',
            'roles' => $dataLst,                      
        ];

        return view('admin.roles.roles_list')->with($params);
    }

    
    /**
     * Show the form for creating new Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $permissions = Permission::all();        
        $params = [
            'title' => 'Create Roles', 
            'permissions' => $permissions,
        ];

        return view('admin.roles.roles_create')->with($params);
    }

    /**
     * Store a newly created Permission in storage.
     *
     * @param  \App\Http\Requests\StorePermissionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){       
        $this->validate($request, [
            'name' => 'required|unique:roles',
            'display_name' => 'required',
            'description' => 'required',
            'permission_id'=> 'required',
        ]);

        $role = Role::create([
            'name' => $request->input('name'),
            'display_name' => $request->input('display_name'),
            'description' => $request->input('description'),
            'created_by'=>Auth::user()->id,
        ]);
        $permissions = $request->input('permission_id') ? $request->input('permission_id') : [];
        $role->attachPermission($permissions);
        return redirect()->route('roles.index')->with('success', trans('general.form.flash.created',['name' => $role->name]));
    }


    /**
     * Show the form for editing Permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){        
        $permissions = Permission::all();     
        $roles = Role::findOrFail($id);
        $permissions_select = DB::table('permission_role')
                                  ->leftJoin('permissions', 'permission_role.permission_id', '=', 'permissions.id')         
                                  ->select('permission_role.*', 'permissions.name as permissions_name')       
                                  ->where('permission_role.role_id', '=', $id) 
                                  ->get();


        $params = [
            'title' => 'Edit Permissions',
            'roles' => $roles,
            'permissions' => $permissions,
            'permissions_select' => $permissions_select,
        ];
        return view('admin.roles.roles_edit')->with($params);
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
            'name' => 'required|unique:roles,name,'.$id,
            'display_name' => 'required',
            'description' => 'required',
            'permission_id'=> 'required',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->updated_by =  Auth::user()->id;
        $role->save();       
        $permissions = $role->perms;        
        foreach ($permissions as $key => $value) {            
             $role->detachPermission($value);
        }
        $permission = $request->input('permission_id') ? $request->input('permission_id') : [];
        $role->attachPermission($permission);
        return redirect()->route('roles.index')->with('success', trans('general.form.flash.updated',['name' => $role->name]));
    }

    public function show($id){
        try{
            $roles = Role::findOrFail($id);
            $params = [
                'title' => 'Delete Permission',
                'roles' => $roles,
            ];
            return view('admin.roles.roles_delete')->with($params);
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
      $roles = Role::findOrFail($id);
      $roles->delete();
      return redirect()->route('roles.index')->with('success', trans('general.form.flash.deleted',['name' => $roles->name]));
    }

    public function search(Request $request){
        $constraints = [
            'name' => $request['name'],
            'display_name' => $request['display_name'],                      
        ];

        $dataLst = $this->doSearchingQuery($constraints);
        return view('admin/roles/roles_list', ['roles' => $dataLst, 'searchingVals' => $constraints]);
   }

   private function doSearchingQuery($constraints) {
        $query = Role::where('deleted_at', '=', null)->orderBy('id', 'DESC');
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
