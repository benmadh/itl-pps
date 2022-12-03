<?php

namespace Larashop\Http\Controllers\General;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeesController extends Controller
{
    public function getEmpPhoto($employee_id){
        
         $dataLst = DB::table('employees')       
                        ->where('id', '=', $employee_id)
                        ->where('deleted_at', '=', null)
                        ->get();

        return Response::json($dataLst);
    }
}
