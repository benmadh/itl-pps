<?php

namespace Larashop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

use Charts;
use Response;
use Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Larashop\Models\Planning\View_workorders;
use Larashop\Models\Planning\Planningboard;

use Larashop\Models\Planning\Budget;

class DashboardController extends Controller
{
    public function index()
    {

        $params = [
            'title' => 'Dash Board',
        ];

        return view('admin.dashboard.dashboard')->with($params);

    }
}