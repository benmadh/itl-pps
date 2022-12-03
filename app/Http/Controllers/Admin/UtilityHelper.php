<?php

namespace Larashop\Http\Controllers\Admin;

use DB;
use Auth;
use Mail;

use Larashop\Models\User;
use Larashop\Models\Production\Style;
use Larashop\Models\Visitorrequestform;

use Illuminate\Http\Request;
use Larashop\Http\Controllers\Controller;

trait UtilityHelper
{
	public function createSystemLogs($action){		
		
        $this->insertRecords('system_logs',array('created_by'=>Auth::user()->id,
                                            'updated_by'=>Auth::user()->id,
                                            'action'=>$action,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')),false);
    }
    
    public function insertRecords($tableName,$data,$isBulk){
        if($isBulk)
            return DB::table($tableName)->insert($data);
        else
            return DB::table($tableName)->insertGetId($data);
    }

   public function updateRecords($tableName,$idList,$data){
        return DB::table($tableName)
                    ->where('id', $idList)
                    ->update($data);
    }

    public function getLastRecord($modelName){
        if($modelName==='Style'){
            return Style::orderBy('id', 'desc')->first();

        }
        if($modelName==='Visitorrequestform'){
            return Visitorrequestform::orderBy('id', 'desc')->first();

        }
        return null;
    }
    
}
