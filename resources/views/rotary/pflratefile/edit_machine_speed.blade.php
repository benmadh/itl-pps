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
                            <h2>Update Machine Speed</h2>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2> <a href="{{route('pflratefile.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('pflratefile.update_machine_speed', ['id' => $dataObj->id]) }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}                        
                        <div class="row">
                            <div class="x_panel">
                                <div class ="col-md-3">
                                    <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="company_id">@lang('general.common.company') 
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-8">
                                            <input type="text" value="{{$dataObj->companies->name}}" id="name" name="name" class="form-control col-md-7 col-xs-12" readonly>                                                
                                        </div>
                                    </div>
                                </div>

                                <div class ="col-md-3">
                                    <div class="form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="location_id">@lang('general.common.location') 
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-8">
                                            <input type="text" value="{{$dataObj->locations->name}}" id="name" name="name" class="form-control col-md-7 col-xs-12" readonly>                                           
                                        </div>
                                    </div>
                                </div>
                                
                                <div class ="col-md-3">
                                    <div class="form-group{{ $errors->has('machine_type_id') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-5 col-sm-5 col-xs-5" for="machine_type_id">@lang('general.common.machine_type') 
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <input type="text" value="{{$dataObj->machine_types->name}}" id="name" name="name" class="form-control col-md-7 col-xs-12" readonly>                                            
                                        </div>
                                    </div>
                                </div>

                                <div class ="col-md-3">
                                    <div class="form-group{{ $errors->has('table_type') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="table_type">@lang('general.common.table_type')  
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <input type="text" value="{{$dataObj->table_type}}" id="name" name="name" class="form-control col-md-7 col-xs-12" readonly>                                       
                                        </div>
                                    </div> 
                                </div>  
                            </div>                         
                        </div>                    
                        <div class="row">
                            <div class="x_panel">
                                 <div class="form-group{{ $errors->has('total_amount3') ? ' has-error' : '' }}">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" style="font-size: 20px; padding: 12px 20px; margin: 8px 0; box-sizing: border-box; border: none; background-color: #3CBC8D; color: white;" value="Machine Speed (Metres per Hour)" id="total_amount3" name="total_amount3" class="form-control text-left input-sm">                                            
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
                                            @if (count($dataRw)) 
                                                @foreach($dataRw as $rowRw)
                                                    <tr>                                                        
                                                        <td>
                                                            <input type="text" value="{{$rowRw['from_ord_qty']}}" name="from_ord_qty_rw[]" class="integer form-control text-right" id="from_ord_qty_rw{{$loop->index}}" >
                                                        </td>
                                                        <td>                                                            
                                                            <input type="text" value="{{$rowRw['to_ord_qty']}}" name="to_ord_qty_rw[]" class="integer form-control text-right" id="to_ord_qty_rw{{$loop->index}}" >
                                                        </td>

                                                        <td>                                                            
                                                            <input type="text" value="{{$rowRw['batch_size_cat_ref']}}" name="batch_size_cat_ref[]" class="decimal form-control text-right" id="batch_size_cat_ref{{$loop->index}}" >
                                                        </td>

                                                        @foreach($substrateCategoryData as $rowSub) 
                                                            <td>
                                                                <input type="text" value="{{$rowRw[$rowSub->id]}}" name="{{$rowSub->id}}[]" id="{{$rowSub->id}}{{$loop->index}}" class="decimal form-control text-right">
                                                            </td>
                                                        @endforeach                                                                                                     
                                                        <td>
                                                            <button type="button" name="btn_remove" id="btn_remove" class="btn btn-danger btn_remove">X</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>                                                        
                                                    <td>
                                                        <input type="text" name="from_ord_qty_rw[]" class="integer form-control text-right" id="from_ord_qty_rw0" >
                                                    </td>
                                                    <td>                                                            
                                                        <input type="text" name="to_ord_qty_rw[]" class="integer form-control text-right" id="to_ord_qty_rw0" >
                                                    </td>

                                                    <td>                                                            
                                                        <input type="text" name="batch_size_cat_ref[]" class="decimal form-control text-right" id="batch_size_cat_ref0" >
                                                    </td>

                                                    @foreach($substrateCategoryData as $rowSub) 
                                                        <td>
                                                            <input type="text" name="{{$rowSub->id}}[]" id="{{$rowSub->id}}0" class="decimal form-control text-right">
                                                        </td>
                                                    @endforeach                                                                                                     
                                                    <td>
                                                        <button type="button" name="btn_remove" id="btn_remove" class="btn btn-danger btn_remove">X</button>
                                                    </td>
                                                </tr>
                                            @endif 
                                        </tbody>
                                    </table>
                                </div> 
                            </div>
                        </div>
                       
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">                              
                                <button type="submit" id ="update_machine"class="btn btn-success">@lang('general.form.save_changes')</button>
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
    </script>
@stop