<?php

namespace Larashop\Http\Controllers\Admin;

use Larashop\Models\User;
use Larashop\Models\Role;
use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Larashop\Models\General\Location;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     */
    public function __construct(){
        $this->middleware('permission:users_access_allow');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){          
        $dataLst = User::where('deleted_at', '=', null)
                     ->orderBy('id', 'DESC')  
                     ->limit(10)                                   
                     ->get();

        $locations = Location::all();
        $searchingVals = null;
        $params = [
            'title'     =>  'Users Listing',
            'dataLst'   =>  $dataLst,
            'locations' =>  $locations,
            'searchingVals' => $searchingVals, 
        ];        
        return view('admin.users.users_list')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $roles = Role::all();  
        $locations = Location::all();     
        $params = [
            'title' => 'Create User',            
            'roles' => $roles,  
            'locations' =>  $locations,          
        ];

        return view('admin.users.users_create')->with($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $this->validate($request, [
            'locations_id' => 'required',
            'name' => 'required',
            'user_name' => 'required|min:15|unique:users',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ]);

        $user = User::create([
            'locations_id' => $request->input('locations_id'),
            'user_name' => trim($request->input('user_name')),
            'name' => trim($request->input('name')),
            'email' => trim($request->input('email')),
            'password' => bcrypt($request->input('password')),
            'created_by'=>Auth::user()->id,
        ]);       
        $user->roles()->attach($request->role_id);
        return redirect()->route('users.index')->with('success', trans('general.form.flash.created',['name' => $user->name]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        try{
            $user = User::findOrFail($id);
            $params = [
                'title' => 'Delete User',
                'user' => $user,
            ];
            return view('admin.users.users_delete')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }
        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        try{
            $user = User::findOrFail($id);
            $roles = Role::all();
            $locations = Location::all();
            $params = [
                'title' => 'Edit User',
                'user'  => $user,
                'roles' => $roles,
                'locations' => $locations,
            ];
            return view('admin.users.users_edit')->with($params);
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        try{
            $user = User::findOrFail($id);           
            $this->validate($request, [
                'locations_id' => 'required',
                'name' => 'required',
                'user_name' => 'required|unique:users,user_name,'.$id,
                'email' => 'required|email',
            ]);  

            $user->locations_id = $request->input('locations_id');
            $user->user_name = trim($request->input('user_name'));          
            $user->name = trim($request->input('name'));
            $user->email = trim($request->input('email'));
            $user->updated_by =  Auth::user()->id;
            $user->save();
            $roles = $user->roles;
            foreach ($roles as $key => $value) {
                $user->detachRole($value);
            }          
            $user->roles()->attach($request->role_id);
            return redirect()->route('users.index')->with('success', trans('general.form.flash.updated',['name' => $user->name]));
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try{
            $updData = User::findOrFail($id);
            $updData->deleted_by =  Auth::user()->id;
            $updData->save();

            $delData = User::findOrFail($id);            
            $delData->delete();
            $delData->roles()->detach($delData->roles);

            return redirect()->route('users.index')->with('success', trans('general.form.flash.deleted',['name' => $delData->name]));
        }catch (ModelNotFoundException $ex){
            if ($ex instanceof ModelNotFoundException){
                return response()->view('errors.'.'404');
            }
        }
    }

    public function search(Request $request){
        $constraints = [
            'user_name' => $request['user_name'],
            'name' => $request['name'],
            'email' => $request['email'],
            'locations_id' => $request['locations_id'],                      
        ];
        $dataLst = $this->doSearchingQuery($constraints);
        $locations = Location::all();
        return view('admin/users/users_list', ['dataLst' => $dataLst,'locations' => $locations, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = User::where('deleted_at', '=', null)->orderBy('id', 'DESC');
        $fields = array_keys($constraints);
        $index = 0; 
        foreach ($constraints as $constraint) {
            if ($fields[$index] == 'user_name') {
                if ($constraint != null) { 
                    $query = $query->where('user_name', 'like', '%'.trim($constraint).'%');
                } 
            }  
            if ($fields[$index] == 'name') {
                if ($constraint != null) { 
                    $query = $query->where('name', 'like', '%'.trim($constraint).'%');
                } 
            }                            
            if ($fields[$index] == 'email') {
                if ($constraint != null) { 
                    $query = $query->where('email', 'like', '%'.trim($constraint).'%');                       
                } 
            }

            if ($fields[$index] == 'locations_id') {
                if ($constraint != null) { 
                    $query = $query->where('locations_id', '=', $constraint);                      
                }
            } 
            
            $index++;
        }       
        return $query->paginate(100000);
    }
}
