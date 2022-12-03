<?php

namespace Larashop\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GarmentComp extends Model
{
    use SoftDeletes;  
    protected $dates = ['deleted_at'];
    protected $fillable = [        
        'work_order_header_id',
        'fibre_string',
        'fibrecontent1',
        'fibrecontent2',
        'fibrecontent3',
        'fibrecontent4',
        'fibrecontent5',
        'fibrecontent6',
        'fibrecontent7',
        'fibrecontent8',
        'garment_comp_chinese', ,
        'fibrecontent1_chinese',
        'fibrecontent2_chinese',
        'fibrecontent3_chinese',
        'fibrecontent4_chinese',
        'fibrecontent5_chinese',
        'fibrecontent6_chinese',
        'fibrecontent7_chinese',
        'fibrecontent8_chinese',           
        'garment_compadd_chinese', 
        'fibrecontentadd9_chinese',
        'fibrecontentadd10_chinese',
        'garment_compadd',
        'fibrecontentadd9',
        'fibrecontentadd10',
        'fibrecontentadd11',
        'fibrecontentadd12',
        'fibrecontentadd13',
        'fibrecontentadd14',
        'fibrecontentadd15',
        'fibrecontentadd16',
        'fibrecontentadd17',
        'fibrecontentadd18',
        'fibrecontentadd19',
        'fibrecontentadd20',
        'evenmore_garment_compadd_chinese', 
        'fibrecontentadd11_chinese',
        'fibrecontentadd12_chinese',
        'fibrecontentadd13_chinese',
        'fibrecontentadd14_chinese',
        'fibrecontentadd15_chinese',
        'fibrecontentadd16_chinese',
        'fibrecontentadd17_chinese',
        'fibrecontentadd18_chinese',
        'fibrecontentadd19_chinese',
        'fibrecontentadd20_chinese',
        'instruction_chinese',
        'carephrase_string',
        'carephrase_string_chinese',
        'set2_carephrase_string',
        'set2_carephrase_string_chinese',
        'evenmore_garment_compadd',        
        'garment_compadd',
        'MadeIn',
        'madein_french',
        'madein_spanish',
        'instruction',
        'madein_italian',
        'madein_chinese',
        'created_by',
        'updated_by',
        'deleted_by',
    ]; 

    public function work_order_headers(){
        return $this->belongsTo(WorkOrderHeader::class,'work_order_header_id');
    }

    public function userCreateInfo(){
        return $this->belongsTo('Larashop\Models\User','created_by');
    }

    public function userUpdateInfo(){
        return $this->belongsTo('Larashop\Models\User','updated_by');
    } 
}
  

