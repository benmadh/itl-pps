<?php

namespace Larashop\Http\Controllers\Auth;


use Validator;
use Larashop\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Larashop\Models\User;
use Larashop\Models\Role;
use Larashop\Models\General\Employee;
use Illuminate\Support\Facades\Gate;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [            
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|min:6|confirmed',
            'employeeid' => 'required|numeric',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data){

        $employee_id = $data['employeeid'];
        $empDetails = Employee::findOrFail($employee_id);
        $user_name=$empDetails->name;
        //dd($user_name);   

        $user = User::create([
            'name' => $user_name,
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'employeeid' => $employee_id,
        ]);

        $role_id=23; 
        $role = Role::find($role_id);            
        $user->attachRole($role);
        return $user;
    }
}
