@extends('templates.admin.layout')
@section('content')
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-11 col-sm-11 col-xs-11">
                            <h2>@lang('pflratefile.create')</h2>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2> <a href="{{route('pflratefile.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('pflratefile.store') }}" data-parsley-validate class="form-horizontal form-label-left">
                        <div class="row">
                            <div class="x_panel">
                                <div class ="col-md-3">
                                    <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="company_id">@lang('general.common.company') <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-8">
                                            <select class="js-example-basic-single form-control" id="company_id" name="company_id" onchange="getLocationList()">
                                                @if(count($comData)) 
                                                <option value=""></option>                                       
                                                    @foreach($comData as $row)
                                                        <option value="{{$row->id}}">{{$row->name}} </option>
                                                    @endforeach
                                                @endif
                                            </select>  
                                             @if ($errors->has('company_id'))
                                                <span class="help-block">{{ $errors->first('company_id') }}</span>
                                            @endif                                         
                                        </div>
                                    </div>
                                </div>

                                <div class ="col-md-3">
                                    <div class="form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="location_id">@lang('general.common.location') <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-8">
                                            <select class="form-control" id="location_id" name="location_id">
                                                <option value=""></option>                                              
                                            </select> 
                                            @if ($errors->has('location_id'))
                                                <span class="help-block">{{ $errors->first('location_id') }}</span>
                                            @endif                                          
                                        </div>
                                    </div> 
                                </div>

                                <div class ="col-md-3">
                                    <div class="form-group{{ $errors->has('machine_type_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-5 col-sm-5 col-xs-5" for="machine_type_id">@lang('general.common.machine_type') <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <select class="js-example-basic-single form-control" id="machine_type_id" name="machine_type_id">
                                                @if(count($mctData))                                        
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
                                </div>

                                <div class ="col-md-3">
                                    <div class="form-group{{ $errors->has('table_type') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="table_type">@lang('general.common.calculation_metrics')  <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <select class="js-example-basic-single form-control" id="table_type" name="table_type">
                                                @if(count($tableTypeLst))                                        
                                                    @foreach($tableTypeLst as $row)
                                                        <option value="{{$row}}" {{$row == Request::old('table_type') ? 'selected="selected"' : ''}}>{{$row}}</option>
                                                    @endforeach
                                                @endif
                                            </select>  
                                            @if ($errors->has('table_type'))
                                                <span class="help-block">{{ $errors->first('table_type') }}</span>
                                            @endif                                         
                                        </div>
                                    </div> 
                                </div>  
                            </div>                         
                        </div>
                        
                        <div class="row">
                            <div class="x_panel">
                                <div class ="col-md-4">
                                    <div class="row">
                                        <div class ="col-md-5">
                                        </div>
                                        <div class ="col-md-3">
                                            <label>Waste (metres) </label>
                                        </div>
                                        <div class ="col-md-3">
                                            <label>Setup Time (min) </label>
                                        </div>
                                   </div>
                                    <div class="row">
                                        <div class ="col-md-5">
                                            <label>Setup Each Clr (Front) </label> <span class="required">*</span>
                                        </div>
                                        <div class ="col-md-3">
                                            <div class="form-group{{ $errors->has('each_clr_front_waste_mtr') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ Request::old('each_clr_front_waste_mtr') ?: '' }}" id="each_clr_front_waste_mtr" name="each_clr_front_waste_mtr" class="decimal form-control text-right" >
                                                @if ($errors->has('each_clr_front_waste_mtr'))
                                                    <span class="help-block">{{ $errors->first('each_clr_front_waste_mtr') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class ="col-md-3">
                                            <div class="form-group{{ $errors->has('each_clr_front_setup_time_min') ? ' has-error' : '' }}"> 
                                                <input type="text" value="{{ Request::old('each_clr_front_setup_time_min') ?: '' }}" id="each_clr_front_setup_time_min" name="each_clr_front_setup_time_min" class="integer form-control text-right">
                                                @if ($errors->has('each_clr_front_setup_time_min'))
                                                    <span class="help-block">{{ $errors->first('each_clr_front_setup_time_min') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class ="col-md-5">
                                            <label>Setup Each Clr (Back) </label>
                                        </div>
                                        <div class ="col-md-3">
                                            <div class="form-group{{ $errors->has('each_clr_back_waste_mtr') ? ' has-error' : '' }}">                                           
                                                <input type="text" value="{{ Request::old('each_clr_back_waste_mtr') ?: '' }}" id="each_clr_back_waste_mtr" name="each_clr_back_waste_mtr" class="decimal form-control text-right" >
                                                @if ($errors->has('each_clr_back_waste_mtr'))
                                                    <span class="help-block">{{ $errors->first('each_clr_back_waste_mtr') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class ="col-md-3">
                                            <div class="form-group{{ $errors->has('each_clr_back_setup_time_min') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ Request::old('each_clr_back_setup_time_min') ?: '' }}" id="each_clr_back_setup_time_min" name="each_clr_back_setup_time_min" class="integer form-control text-right" >
                                                @if ($errors->has('each_clr_back_setup_time_min'))
                                                    <span class="help-block">{{ $errors->first('each_clr_back_setup_time_min') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class ="col-md-5">
                                            <label>Plate Change </label>
                                        </div>
                                        <div class ="col-md-3">
                                            <div class="form-group{{ $errors->has('plate_change_waste_mtr') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ Request::old('plate_change_waste_mtr') ?: '' }}" id="plate_change_waste_mtr" name="plate_change_waste_mtr" class="decimal form-control text-right">
                                                @if ($errors->has('plate_change_waste_mtr'))
                                                    <span class="help-block">{{ $errors->first('plate_change_waste_mtr') }}</span>
                                                @endif                                           
                                            </div>
                                        </div>
                                        <div class ="col-md-3">
                                            <div class="form-group{{ $errors->has('plate_change_setup_time_min') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ Request::old('plate_change_setup_time_min') ?: '' }}" id="plate_change_setup_time_min" name="plate_change_setup_time_min" class="integer form-control text-right">
                                                @if ($errors->has('plate_change_setup_time_min'))
                                                    <span class="help-block">{{ $errors->first('plate_change_setup_time_min') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class ="col-md-5">
                                            <label>Tape Setup  </label>
                                        </div>
                                        <div class ="col-md-3">
                                            <div class="form-group{{ $errors->has('tape_setup_waste_mtr') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ Request::old('tape_setup_waste_mtr') ?: '' }}" id="tape_setup_waste_mtr" name="tape_setup_waste_mtr" class="decimal form-control text-right">
                                                @if ($errors->has('tape_setup_waste_mtr'))
                                                    <span class="help-block">{{ $errors->first('tape_setup_waste_mtr') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class ="col-md-3">
                                            <div class="form-group{{ $errors->has('tape_setup_setup_time_min') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ Request::old('tape_setup_setup_time_min') ?: '' }}" id="tape_setup_setup_time_min" name="tape_setup_setup_time_min" class="integer form-control text-right">
                                                @if ($errors->has('tape_setup_setup_time_min'))
                                                    <span class="help-block">{{ $errors->first('tape_setup_setup_time_min') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                   <div class="row">
                                        <div class ="col-md-5">
                                            <label>Reference Change  </label>
                                        </div>
                                        <div class ="col-md-3">
                                        </div>
                                        <div class ="col-md-3">
                                            <div class="form-group{{ $errors->has('reference_change_time_min') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ Request::old('reference_change_time_min') ?: '' }}" id="reference_change_time_min" name="reference_change_time_min" class="integer form-control text-right">
                                                @if ($errors->has('reference_change_time_min'))
                                                    <span class="help-block">{{ $errors->first('reference_change_time_min') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                   </div>
                                </div>
                                
                                <div class ="col-md-3">
                                    <div class="x_panel">
                                         <div class="form-group{{ $errors->has('total_amount1') ? ' has-error' : '' }}">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" style="font-size: 20px; padding: 12px 20px; margin: 8px 0; box-sizing: border-box; border: none; background-color: #3CBC8D; color: white;" value="Setup waste for Cutting" id="total_amount1" name="total_amount1" class="form-control text-left input-sm">                                            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <table class="table table-bordered" id="table_cutting_method" >
                                                <thead>
                                                    <tr>
                                                        <th>Cutting Method</th>
                                                        <th>C & F (Pcs)</th>                                               
                                                    </tr>
                                                </thead>

                                                <tfoot>
                                                    <tr>
                                                        <th style="border: none;"></th>
                                                        <th style="border: none;"></th>                                                                     
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    @if (count($cuttingMethodData)) 
                                                        @foreach($cuttingMethodData as $row)
                                                            <tr> 
                                                                <td>
                                                                    <input type="hidden" value="{{ $row->id }}" id="cm_id{{$loop->index}}" name="cm_id[]" >
                                                                    <input type="text" value="{{$row->name}}" id="cm_name{{$loop->index}}" name="cm_name[]" class="form-control cm_name" disabled>
                                                                </td>  
                                                                <td>
                                                                    <input type="text" id="pcs{{$loop->index}}" name="pcs[]" class="integer form-control text-right">
                                                                </td>     
                                                            </tr>
                                                        @endforeach
                                                    @endif 
                                                </tbody>
                                            </table>
                                        </div> 
                                    </div>                                    
                                </div>

                                <div class ="col-md-5">
                                    <div class="x_panel">
                                         <div class="form-group{{ $errors->has('total_amount') ? ' has-error' : '' }}">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" style="font-size: 20px; padding: 12px 20px; margin: 8px 0; box-sizing: border-box; border: none; background-color: #3CBC8D; color: white;" value="Additions to Customers (Per Size)" id="total_amount" name="total_amount" class="form-control text-left input-sm">                                            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <table class="table table-bordered" id="table_bom" >
                                                <thead>
                                                    <tr>
                                                        <th>From Ord Qty</th>
                                                        <th>To Ord Qty</th>
                                                        <th>Percentage</th>
                                                        <th style="text-align: center;background: #eee"><button type="button" name="addRow" id="addRow" class="btn btn-success">+</button></th>                                                                     
                                                    </tr>
                                                </thead>

                                                <tfoot>
                                                    <tr>
                                                        <th style="border: none;"></th>
                                                        <th style="border: none;"></th>
                                                        <th style="border: none;"></th>
                                                        <th style="border: none;"></th>                                                                     
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <tr>                                                        
                                                        <td>                                                            
                                                            <input type="text" name="from_ord_qty[]" id="from_ord_qty0" class="integer form-control text-right">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="to_ord_qty[]" id="to_ord_qty0" class="integer form-control text-right">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="percentage[]" id="percentage0" class="decimal form-control text-right">
                                                        </td>                                                        
                                                        <td>
                                                            <button type="button" name="btn_remove" id="btn_remove" class="btn btn-danger btn_remove">X</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            
                        </div> 

                        <div class="row">
                            <div class="x_panel">
                                 <div class="form-group{{ $errors->has('total_amount3') ? ' has-error' : '' }}">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" style="font-size: 20px; padding: 12px 20px; margin: 8px 0; box-sizing: border-box; border: none; background-color: #3CBC8D; color: white;" value="Running Waste (%)" id="total_amount3" name="total_amount3" class="form-control text-left input-sm">                                            
                                    </div>
                                </div>
                                <div class="form-group">
                                    <table class="table table-bordered" id="table_running_waste" >
                                        <thead>
                                            <tr>
                                                <th>From Ord Qty</th>
                                                <th>To Ord Qty</th> 
                                                <th>Batch Size Category Reference</th>                                               
                                                    @foreach($substrateCategoryData as $row)
                                                        <th>{{$row->name}}</th>
                                                    @endforeach 
                                                <th style="text-align: center;background: #eee"><button type="button" name="addRowRw" id="addRowRw" class="btn btn-success">+</button></th>                                                                     
                                            </tr>
                                        </thead>

                                        <tfoot>
                                            <tr>
                                                <th style="border: none;"></th>
                                                <th style="border: none;"></th>
                                                <th style="border: none;"></th>
                                                @foreach($substrateCategoryData as $row)
                                                    <th style="border: none;"></th>
                                                @endforeach 
                                                <th style="border: none;"></th>                                                                     
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <tr>                                                        
                                                <td>                                                            
                                                    <input type="text" name="from_ord_qty_rw[]" id="from_ord_qty_rw0" class="integer form-control text-right">
                                                </td>
                                                <td>
                                                    <input type="text" name="to_ord_qty_rw[]" id="to_ord_qty_rw0" class="integer form-control text-right">
                                                </td>
                                                <td>
                                                    <input type="text" name="batch_size_cat_ref[]" id="batch_size_cat_ref0" class="decimal form-control text-right">
                                                </td>
                                                @foreach($substrateCategoryData as $row) 
                                                    <td>
                                                        <input type="text" name="{{$row->id}}[]" id="{{$row->id}}0" class="decimal form-control text-right">
                                                    </td>
                                                @endforeach                                                                                                       
                                                <td>
                                                    <button type="button" name="btn_remove" id="btn_remove" class="btn btn-danger btn_remove">X</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div> 
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                                <button type="submit" class="btn btn-success">@lang('general.form.create_record')</button>
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
            $(".js-example-basic-multiple").select2();
            $(".js-example-basic-single").select2();            
            var i=0; 
            $('#addRow').click(function(){            
                i++;                     
                $('#table_bom').append('<tr i="row'+i+'">'+             
                                        '<td>'+
                                        '<input type="text" name="from_ord_qty[]" class="integer form-control text-right" id="from_ord_qty'+i+'" required>'+
                                        '@if ($errors->has('from_ord_qty.'.'+i+'))'+
                                        '<span class="help-block">{{ $errors->first('from_ord_qty.'.'+i+') }}</span>'+
                                        '@endif'+
                                        '</td>'+
                                        '<td>'+
                                        '<input type="text" name="to_ord_qty[]" class="integer form-control text-right" id="to_ord_qty'+i+'" required>'+
                                        '@if ($errors->has('to_ord_qty'))'+
                                        '<span class="help-block">{{ $errors->first('to_ord_qty') }}</span>'+
                                        '@endif'+
                                        '</td>'+
                                        '<td>'+
                                        '<input type="text" name="percentage[]" class="decimal form-control text-right" id="percentage'+i+'" required >'+
                                        '@if ($errors->has('percentage.+i+'))'+
                                        '<span class="help-block">{{ $errors->first('percentage.+i+') }}</span>'+
                                        '@endif'+
                                        '</td>'+                                        
                                        '<td>'+
                                        '<button type="button" name="btn_remove" id="btn_remove" class="btn btn-danger btn_remove">X</button>'+
                                        '</td>'+
                                        '</tr>');  

                $(function() {
                  $("input.decimal").bind("change keyup input", function() {
                    var position = this.selectionStart - 1;
                    //remove all but number and .
                    var fixed = this.value.replace(/[^0-9\.]/g, "");
                    if (fixed.charAt(0) === ".")
                      //can't start with .
                      fixed = fixed.slice(1);

                    var pos = fixed.indexOf(".") + 1;
                    if (pos >= 0)
                      //avoid more than one .
                      fixed = fixed.substr(0, pos) + fixed.slice(pos).replace(".", "");

                    if (this.value !== fixed) {
                      this.value = fixed;
                      this.selectionStart = position;
                      this.selectionEnd = position;
                    }
                  });

                  $("input.integer").bind("change keyup input", function() {
                    var position = this.selectionStart - 1;
                    //remove all but number and .
                    var fixed = this.value.replace(/[^0-9]/g, "");

                    if (this.value !== fixed) {
                      this.value = fixed;
                      this.selectionStart = position;
                      this.selectionEnd = position;
                    }
                  });
                }); 
            });

            $('#addRowRw').click(function(){            
                i++;                     
                $('#table_running_waste').append('<tr i="row'+i+'">'+             
                                        '<td>'+
                                        '<input type="text" name="from_ord_qty_rw[]" class="integer form-control text-right" id="from_ord_qty_rw'+i+'" required>'+
                                        '</td>'+
                                        '<td>'+
                                        '<input type="text" name="to_ord_qty_rw[]" class="integer form-control text-right" id="to_ord_qty_rw'+i+'" required>'+
                                        '</td>'+
                                        '<td>'+
                                        '<input type="text" name="batch_size_cat_ref[]" class="decimal form-control text-right" id="batch_size_cat_ref'+i+'" required>'+
                                        '</td>'+
                                        '@foreach($substrateCategoryData as $row)'+ 
                                        '<td>'+
                                        '<input type="text" name="{{$row->id}}[]" id="{{$row->id}}'+i+'" class="decimal form-control text-right">'+
                                        '</td>'+
                                        '@endforeach'+ 
                                        '<td>'+
                                        '<button type="button" name="btn_remove" id="btn_remove" class="btn btn-danger btn_remove">X</button>'+
                                        '</td>'+
                                        '</tr>');  

                $(function() {
                  $("input.decimal").bind("change keyup input", function() {
                    var position = this.selectionStart - 1;
                    //remove all but number and .
                    var fixed = this.value.replace(/[^0-9\.]/g, "");
                    if (fixed.charAt(0) === ".")
                      //can't start with .
                      fixed = fixed.slice(1);

                    var pos = fixed.indexOf(".") + 1;
                    if (pos >= 0)
                      //avoid more than one .
                      fixed = fixed.substr(0, pos) + fixed.slice(pos).replace(".", "");

                    if (this.value !== fixed) {
                      this.value = fixed;
                      this.selectionStart = position;
                      this.selectionEnd = position;
                    }
                  });

                  $("input.integer").bind("change keyup input", function() {
                    var position = this.selectionStart - 1;
                    //remove all but number and .
                    var fixed = this.value.replace(/[^0-9]/g, "");

                    if (this.value !== fixed) {
                      this.value = fixed;
                      this.selectionStart = position;
                      this.selectionEnd = position;
                    }
                  });
                }); 
            });

            $('#addRowMs').click(function(){            
                i++;                     
                $('#table_machine_speed').append('<tr i="row'+i+'">'+             
                                        '<td>'+
                                        '<input type="text" name="from_ord_qty_ms[]" class="integer form-control text-right" id="from_ord_qty_ms'+i+'" required>'+
                                        '</td>'+
                                        '<td>'+
                                        '<input type="text" name="to_ord_qty_ms[]" class="integer form-control text-right" id="to_ord_qty_ms'+i+'" required>'+
                                        '</td>'+
                                        '<td>'+
                                        '<input type="text" name="batch_size_cat_ref_ms[]" class="decimal form-control text-right" id="batch_size_cat_ref_ms'+i+'" required>'+
                                        '</td>'+
                                        '@foreach($substrateCategoryData as $row)'+ 
                                        '<td>'+
                                        '<input type="text" name="{{$row->id}}[]" id="{{$row->id}}'+i+'" class="decimal form-control text-right">'+
                                        '</td>'+
                                        '@endforeach'+ 
                                        '<td>'+
                                        '<button type="button" name="btn_remove" id="btn_remove" class="btn btn-danger btn_remove">X</button>'+
                                        '</td>'+
                                        '</tr>');  

                $(function() {
                  $("input.decimal").bind("change keyup input", function() {
                    var position = this.selectionStart - 1;
                    //remove all but number and .
                    var fixed = this.value.replace(/[^0-9\.]/g, "");
                    if (fixed.charAt(0) === ".")
                      //can't start with .
                      fixed = fixed.slice(1);

                    var pos = fixed.indexOf(".") + 1;
                    if (pos >= 0)
                      //avoid more than one .
                      fixed = fixed.substr(0, pos) + fixed.slice(pos).replace(".", "");

                    if (this.value !== fixed) {
                      this.value = fixed;
                      this.selectionStart = position;
                      this.selectionEnd = position;
                    }
                  });

                  $("input.integer").bind("change keyup input", function() {
                    var position = this.selectionStart - 1;
                    //remove all but number and .
                    var fixed = this.value.replace(/[^0-9]/g, "");

                    if (this.value !== fixed) {
                      this.value = fixed;
                      this.selectionStart = position;
                      this.selectionEnd = position;
                    }
                  });
                }); 
            });

            $(document).on('click','.btn_remove',function(){
                $(this).parent().parent().remove();                
            });
        });

        $(function() {
          $("input.decimal").bind("change keyup input", function() {
            var position = this.selectionStart - 1;
            //remove all but number and .
            var fixed = this.value.replace(/[^0-9\.]/g, "");
            if (fixed.charAt(0) === ".")
              //can't start with .
              fixed = fixed.slice(1);

            var pos = fixed.indexOf(".") + 1;
            if (pos >= 0)
              //avoid more than one .
              fixed = fixed.substr(0, pos) + fixed.slice(pos).replace(".", "");

            if (this.value !== fixed) {
              this.value = fixed;
              this.selectionStart = position;
              this.selectionEnd = position;
            }
          });

          $("input.integer").bind("change keyup input", function() {
            var position = this.selectionStart - 1;
            //remove all but number and .
            var fixed = this.value.replace(/[^0-9]/g, "");

            if (this.value !== fixed) {
              this.value = fixed;
              this.selectionStart = position;
              this.selectionEnd = position;
            }
          });
        }); 

        function getLocationList() {
            var company_id = document.getElementById("company_id").value;           
            $.get('{{ url('getLocationList') }}/'+company_id, function(data){ 
                $('#location_id').empty();
                $('#location_id').append('<option value=""></option>');            
                $.each(data,function(index, dataObj){                    
                   $('#location_id').append('<option value="'+dataObj.id+'">'+dataObj.name+'</option>');
                });                
            });
        }

    </script>
@stop