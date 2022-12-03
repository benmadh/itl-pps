@extends('templates.admin.layout')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-11 col-sm-10 col-xs-10">
                            <h2>M R N For PFL Rotary</h2>
                        </div> 
                        <div class="col-md-1 col-sm-2 col-xs-2">                            
                            <h2><a class="btn btn-info btn-xs" data-toggle="modal" data-target="#searchModal" title="Search Records"><i class="fa fa-search"></i> @lang('general.app.search') </a></h2>
                        </div>               
                    </div>                    
                    <div class="clearfix"></div>
                </div>

                <div class="form-group"> 
                    <form method="post" action="" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}                        
                        <div class="row">
                            <div class ="col-md-5">
                                <div class="x_panel">
                                    <div class="form-group{{ $errors->has('main_workorder_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="main_workorder_id">Work Order # 
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <input type="hidden" id="status_id" name="status_id" >
                                            <input type="hidden" id="item_id" name="item_id" >
                                            <input type="hidden" id="sbc_id" name="sbc_id" >
                                            <select class="js-example-basic-single form-control" id="main_workorder_id" name="main_workorder_id" onchange="getWoDetails()">
                                                @if(count($dataObjWo))
                                                    <option value=""></option>
                                                    @foreach($dataObjWo as $row)
                                                         <option value="{{$row->main_workorder_id}}" >{{$row->main_workorder_no}}</option>
                                                    @endforeach
                                                @endif
                                            </select> 
                                            @if ($errors->has('main_workorder_id'))
                                                <span class="help-block">{{ $errors->first('main_workorder_id') }}</span>
                                            @endif
                                        </div>
                                    </div>                                    
                                   
                                    <div class="form-group{{ $errors->has('machine_type_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="machine_type_id">@lang('general.common.machine_type') <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <select class="js-example-basic-single form-control" id="machine_type_id" name="machine_type_id">
                                                @if(count($mctData))  
                                                    <option value=""></option>                                      
                                                    @foreach($mctData as $row)
                                                        <option value="{{$row->id}}" {{$row->id == Request::old('machine_type_id') ? 'selected="selected"' : ''}}>{{$row->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>  
                                             @if ($errors->has('machine_type_id'))
                                                <span class="help-block">{{ $errors->first('machine_type_id') }}</span>
                                            @endif                                         
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('fold_type') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-4 col-xs-3" for="fold_type">Fold Type <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <select class="js-example-basic-single form-control" id="fold_type" name="fold_type">
                                                @if(count($cuttingMethodData))    
                                                    <option value=""></option>                                    
                                                    @foreach($cuttingMethodData as $row)
                                                        <option value="{{$row->id}}" {{$row->name == Request::old('fold_type') ? 'selected="selected"' : ''}}>{{$row->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>  
                                             @if ($errors->has('fold_type'))
                                                <span class="help-block">{{ $errors->first('fold_type') }}</span>
                                            @endif                                         
                                        </div>
                                    </div>                                   

                                    <div class="form-group{{ $errors->has('item_name') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="item_name">Substrate 
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <input type="text" value="{{ Request::old('item_name') ?: '' }}" id="item_name" name="item_name" class="form-control col-md-7 col-xs-7" disabled="">
                                            @if ($errors->has('item_name'))
                                            <span class="help-block">{{ $errors->first('item_name') }}</span>
                                            @endif
                                        </div>
                                    </div> 

                                    <div class="form-group{{ $errors->has('sbc_name') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="sbc_name">Substrate Category
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <input type="text" value="{{ Request::old('sbc_name') ?: '' }}" id="sbc_name" name="sbc_name" class="form-control col-md-7 col-xs-7" disabled="">
                                            @if ($errors->has('sbc_name'))
                                            <span class="help-block">{{ $errors->first('sbc_name') }}</span>
                                            @endif
                                        </div>
                                    </div>                                   

                                    <div class="form-group{{ $errors->has('wo_quantity_tot') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="wo_quantity_tot">Total WO Qty 
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <input type="text" value="{{ Request::old('wo_quantity_tot') ?: '' }}" id="wo_quantity_tot" name="wo_quantity_tot" class="form-control col-md-7 col-xs-7" disabled="">
                                            @if ($errors->has('wo_quantity_tot'))
                                            <span class="help-block">{{ $errors->first('wo_quantity_tot') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('lenght') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="lenght">Label Length 
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <input type="text" value="{{ Request::old('lenght') ?: '0' }}" id="lenght" name="lenght" class="form-control col-md-7 col-xs-12">
                                            @if ($errors->has('lenght'))
                                            <span class="help-block">{{ $errors->first('lenght') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('width') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="width">Label Width 
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <input type="text" value="{{ Request::old('width') ?: '0' }}" id="width" name="width" class="form-control col-md-7 col-xs-12">
                                            @if ($errors->has('width'))
                                            <span class="help-block">{{ $errors->first('width') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('no_front_colour') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="no_front_colour">No of Front Colour 
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <input type="text" value="{{ Request::old('no_front_colour') ?: '0' }}" id="no_front_colour" name="no_front_colour" class="form-control col-md-7 col-xs-12">
                                            @if ($errors->has('no_front_colour'))
                                            <span class="help-block">{{ $errors->first('no_front_colour') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('no_back_colour') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="no_back_colour">No of Back Colour 
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <input type="text" value="{{ Request::old('no_back_colour') ?: '0' }}" id="no_back_colour" name="no_back_colour" class="form-control col-md-7 col-xs-12">
                                            @if ($errors->has('no_back_colour'))
                                            <span class="help-block">{{ $errors->first('no_back_colour') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('label_type') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="label_type">Label Type <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <select class="js-example-basic-single form-control" id="label_type" name="label_type">
                                                @if(count($labelTypeList))
                                                    @foreach($labelTypeList as $row)
                                                        <option value="{{$row}}">{{$row}}</option>
                                                    @endforeach
                                                @endif
                                            </select>                                            
                                        </div>
                                    </div>

                                    <br />
                                    <br />
                                    <br />
                                    <div class="box-footer">                                    
                                        <button type="submit" class="btn btn-success" name="action" value="print">
                                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                            @lang('general.form.print')
                                        </button>

                                    </div>

                                </div>                   
                            </div> 

                            <div class ="col-md-7">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Size Wise Details</h3>
                                    </div>

                                    <div class="panel-body">
                                        <table class="table table-bordered" >
                                            <thead>                                           
                                                <th>Work Order #</th>
                                                <th>Quantity</th> 
                                                <th>Delivery Date</th>
                                                <th>Size Details</th>                                           
                                            </thead>
                                            <tbody id ="woh_des">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div>
                                @if($searchingVals['item_bom_status']=="N")                                       
                                    <table id="customers" class="table table-striped table-bordered">
                                        <tr>
                                            <th colspan="7" style="text-align: left; background-color:Tomato; font-size: 20px; " ><font color="#FFFFFF">{{ $searchingVals['references_name'] }}</font></th>
                                        </tr>                                                                       
                                    </table>
                                @endif
                            </div> 
                        </div>

                        <div class="row">
                             <div class="x_content">
                                <table id="datatable-buttons" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>                                
                                            <th>@lang('general.common.action')</th> 
                                            <th>@lang('general.common.machine_type')</th>
                                            <th>@lang('general.common.table_type')</th>
                                            <th>@lang('general.common.date')</th>
                                            <th>@lang('general.common.workorder_no')</th>
                                            <th>@lang('general.common.quantity')</th>                                
                                            <th>@lang('general.common.standard_material')</th>
                                            <th>@lang('general.common.wastage')</th>
                                            <th>@lang('general.common.material_with_wastage')</th>
                                            <th>@lang('general.common.oee')</th>
                                            <th>@lang('general.common.performance')</th>
                                            <th>@lang('general.common.quality')</th>
                                            <th>@lang('general.common.availability')</th>
                                            <th>@lang('general.common.lenght')</th>  
                                            <th>@lang('general.common.no_of_clr_front')</th>
                                            <th>@lang('general.common.no_of_clr_back')</th> 
                                            <th>@lang('general.common.size_changes')</th>   
                                            <th>@lang('general.form.create_user')</th>
                                            <th>@lang('general.form.create_at')</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>@lang('general.common.action')</th> 
                                            <th>@lang('general.common.machine_type')</th>
                                            <th>@lang('general.common.table_type')</th>
                                            <th>@lang('general.common.date')</th>
                                            <th>@lang('general.common.workorder_no')</th>
                                            <th>@lang('general.common.quantity')</th>                                
                                            <th>@lang('general.common.standard_material')</th>
                                            <th>@lang('general.common.wastage')</th>
                                            <th>@lang('general.common.material_with_wastage')</th>
                                            <th>@lang('general.common.oee')</th>
                                            <th>@lang('general.common.performance')</th>
                                            <th>@lang('general.common.quality')</th>
                                            <th>@lang('general.common.availability')</th>
                                            <th>@lang('general.common.lenght')</th>
                                            <th>@lang('general.common.no_of_clr_front')</th>
                                            <th>@lang('general.common.no_of_clr_back')</th> 
                                            <th>@lang('general.common.size_changes')</th>   
                                            <th>@lang('general.form.create_user')</th>
                                            <th>@lang('general.form.create_at')</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @if (count($dataLst))                               
                                            @foreach($dataLst as $row)
                                                <tr>                               
                                                    <td>                                   
                                                        <a href="{{ route('mrnRotary.edit', ['id' => $row->work_order_header_id]) }}" class="btn btn-success btn-xs"><i class="fa fa-eye" title="Edit Record"></i> </a>
                                                        <a href="{{ route('mrnRotary.print_mrn', ['id' => $row->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-print" title="Print MRN"></i> </a> 
                                                    </td> 
                                                    <td>{{$row->machine_types_name}}</td>
                                                    <td>{{$row->table_type}}</td>   
                                                    <td>{{$row->date}}</td> 
                                                    <td>{{$row->workorder_no}}</td>
                                                    <td>{{$row->total_quantity}}</td>
                                                    <td>{{$row->cal_ribbon_mtr}}</td>
                                                    <td>{{$row->total_material_issued_mtr - $row->cal_ribbon_mtr}}</td>
                                                    <td>{{$row->total_material_issued_mtr}}</td>
                                                    <td>{{$row->cal_oee_percentage}}</td>
                                                    <td>{{$row->cal_performance_percentage}}</td>
                                                    <td>{{$row->cal_quality_percentage}}</td>
                                                    <td>{{$row->cal_availability_percentage}}</td>
                                                    <td>{{$row->lenght}}</td> 
                                                    <td>{{$row->no_of_colors_front}}</td> 
                                                    <td>{{$row->no_of_colors_back}}</td> 
                                                    <td>{{$row->size_changes}}</td>                                                 
                                                    <td>{{$row->created_by}}</td>
                                                    <td>{{$row->created_at}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div> 

                        <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="seachModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title" id="seachModalLabel">Search Details</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">                            
                                            <div class="form-group{{ $errors->has('wo_no') ? ' has-error' : '' }}">             
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="wo_no">Work Order #</label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <input type="text" value="{{ Request::old('wo_no') ?: '' }}" id="wo_no" name="wo_no" placeholder="Exp: 1323053" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>                                                                
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('machine_type_id_search') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="machine_type_id_search">@lang('general.common.machine_type') 
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-8">
                                            <select class="form-control col-md-7 col-xs-12" id="machine_type_id_search" name="machine_type_id_search">
                                                @if(count($mctData))
                                                <option value=""></option>
                                                    @foreach($mctData as $row)
                                                         <option value="{{$row->id}}" {{$row->id == Request::old('machine_type_id_search') ?: '' ? 'selected="selected"' : ''}}>{{$row->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>                                           
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('table_type_search') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="table_type_search">@lang('general.common.table_type') 
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-8">
                                            <select class="form-control col-md-12 col-xs-12" id="table_type_search" name="table_type_search">
                                                @if(count($tableTypeLst))
                                                <option value=""></option>
                                                    @foreach($tableTypeLst as $row)
                                                        <option value="{{$row}}" {{$row == Request::old('table_type_search') ?: '' ? 'selected="selected"' : ''}}>{{$row}}</option>
                                                    @endforeach
                                                @endif
                                            </select>                                           
                                        </div>
                                    </div>

                                   
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="action" value="search">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            Search
                                        </button>
                                      </a>                          
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>       
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section ('js')
<script>  
    $(document).ready(function() {           
        $(".js-example-basic-single").select2();
    });  

    function getWoDetails() {
        var main_workorder_id = document.getElementById("main_workorder_id").value;
        var lenght = document.getElementById("lenght");
        var width = document.getElementById("width");
        var no_front_colour = document.getElementById("no_front_colour");
        var no_back_colour = document.getElementById("no_back_colour");
        var wo_sts = document.getElementById("wo_sts");        
        var wo_quantity_tot = document.getElementById("wo_quantity_tot");
        var status_id = document.getElementById("status_id");
        var item_name = document.getElementById("item_name");
        var item_category = document.getElementById("item_category");
        

        lenght.value="";
        width.value="";    
        no_front_colour.value=""; 
        no_back_colour.value="";     
        wo_quantity_tot.value="";       
        $('#wo_des').text('');
        $('#wo_sts').text('');
        $('#customers').text('');
        $.get('{{ url('getWoDetais') }}/'+main_workorder_id, function(data){            
            lenght.value = parseFloat(data[0]['labellenght']).toFixed(2);
            width.value = parseFloat(data[0]['labelwidth']).toFixed(2);
            no_front_colour.value =data[0]['no_of_colors_front'];
            no_back_colour.value =data[0]['no_of_colors_back'];
            status_id.value =data[0]['status'];
            wo_quantity_tot.value =data[0]['wo_quantity_tot'];
            item_name.value =data[0]['item_name']; 
            item_id.value =data[0]['item_id'];
            sbc_name.value =data[0]['sbc_name'];     
            sbc_id.value =data[0]['sbc_id'];       

            var data1 =data[0]['woh_des'];           

             $("#woh_des").empty();
            $.each(data1, function(index, val){                   
                $("#woh_des").append('<tr>');                    
                $("#woh_des").append('<td>'+val.workorderno+'</td>');
                $("#woh_des").append('<td>'+val.wo_quantity+'</td>');
                $("#woh_des").append('<td>'+val.deliverydate+'</td>'); 
                $("#woh_des").append('<td>'+val.wollst_array_list+'</td>');                  
                $("#woh_des").append('</tr>');
            });

            var rssts =data[0]['labellenght'];
            if(rssts != "No Result Found"){
                $('#wo_des').text(data[0]['workorderno']);
                $('#wo_des').attr("style", "font-size: 25px;");
                $('#wo_sts').text(data[0]['lenghtStatus']);
                $('#wo_sts').attr("style", "font-size: 21px;");
            }else{
                $('#wo_des').text(data[0]['workorderno']);
                $('#wo_des').attr("style", "font-size: 25px; color: #ff5733 !important");
                $('#wo_sts').text('');
            } 

        });
    }
</script>
@stop