<?php

namespace Larashop\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Hash;
use Validator;

class ChangePasswordController extends Controller
{
      /**
     * Where to redirect users after password is changed.
     *
     * @var string $redirectTo
     */
    protected $redirectTo = 'change_password';

    /**
     * Change password form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        $user = Auth::getUser();

        return view('auth.change_password', compact('user'));
    }

    /**
     * Change password.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
       
        $this->validator($request->all())->validate();

        if (Hash::check($request->get('current_password'), $user->password)) {            
            $user->password = bcrypt($request->get('new_password'));
            $user->save();
            return redirect($this->redirectTo)->with('success', 'Password change successfully!');
        } else {
        	//dd($user);
        	//return redirect($this->redirectTo)->with('success','Current password is incorrect');
        	 $params = [
	            'current_password' => 'Current password is incorrect',	                      
	      ];
            return redirect()->back()->withErrors($params);
        }
    }

    /**
     * Get a validator for an incoming change password request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'current_password' => 'required',
            'new_password' => 'required|min:4|confirmed',
        ]);
    }
}
