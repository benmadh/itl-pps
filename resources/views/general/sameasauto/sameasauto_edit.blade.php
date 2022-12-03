@extends('templates.admin.layout')

@section('content')
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> <a href="{{route('sameasauto.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a> Allocate work orders in to main order</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('sameasauto.update_sameas_auto', ['id' => $workorderheaders->id]) }}" data-parsley-validate class="form-horizontal form-label-left">
                        <div class="row">
                            <div class ="col-md-12">
                                <div class="x_panel"> 
                                    <div class="form-group{{ $errors->has('workorderno') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="workorderno">Main Work Order <span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-8">
                                            <input type="text" value="{{$workorderheaders->workorder_no}}" id="workorderno" name="workorderno" class="form-control col-md-7 col-xs-12" disabled>
                                            @if ($errors->has('workorderno'))
                                                <span class="help-block">{{ $errors->first('workorderno') }}</span>
                                            @endif
                                        </div>
                                    </div>                                     
                                </div>
                            </div>
                        </div>    

                        <div class="row">
                             <div class="col-md-12"> 
                                <div class="x_panel">
                                    <div class="form-group">
                                        <table class="table table-bordered" id="table_bom" >
                                            <thead>
                                                <tr>
                                                    <th>Work Order No</th> 
                                                    <th>Customer Order No</th>  
                                                    <th>Size Changes</th> 
                                                    <th style="text-align: center;background: #eee"></th>           
                                                </tr>
                                            </thead>

                                            <tfoot> </tfoot>
                                            <tbody>                                                
                                                @if(count($sameAsWoList))
                                                    @foreach($sameAsWoList as $row)
                                                        <tr>                                       
                                                            <td>
                                                                <div>
                                                                    <input type="hidden" value="{{ $maxSizeChanWoId }}" id="main_workorder_id" name="main_workorder_id[]" >
                                                                    <input type="hidden" value="{{ $row->id }}" id="wo_id" name="wo_id[]" >
                                                                    <select class="js-example-basic-single form-control" id="workorderheaders_id" name="workorderheaders_id[]">                       
                                                                        <option value="{{$row->id}}" >{{ $row->workorder_no}}</option>      
                                                                    </select>                                                               
                                                                </div>
                                                            </td> 
                                                            <td><input type="text" value="{{$row->customer_order_no}}" name="customerorderno[]" class="form-control customerorderno"></td>  
                                                            
                                                            <td><input type="text" value="{{$row->size_changes}}" name="sizechanges[]" class="form-control sizechanges"></td> 
                                                            <td>
                                                                <div>
                                                                    <select class="js-example-basic-single form-control woType" id="woType{{$loop->index}}" name="woType[]" onchange="genWotLoop({{$loop->index}})">
                                                                        @foreach($wotList as $wtKey => $wtVal)
                                                                            @if($maxSizeChanWoId==$row->id)
                                                                                <option value="{{$wtKey}}" {{$wtKey == 1 ? 'selected="selected"' : ''}} >{{$wtVal}}</option>
                                                                            @else
                                                                                <option value="{{$wtKey}}">{{$wtVal}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>                        
                                                                </div>
                                                            </td> 

                                                            <td><button type="button" name="btn_remove" id="btn_remove" class="btn btn-danger btn_remove">X</button></td>
                                                        </tr>
                                                     @endforeach
                                                 @endif                                              
                                            </tbody>
                                        </table>
                                    </div> 

                                </div>
                                <div class="form-group">
                                    <table id="customers" class="table table-striped table-bordered">
                                        <tr>
                                            <th colspan="7" style="text-align: left; background-color:Tomato; font-size: 20px; " ><font color="{{$colour_code}}">{{$same_as_status}}</font></th>
                                        </tr>                                                                       
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
        $(".js-example-basic-single").select2();
       
        $(document).on('click','.btn_remove',function(){
            $(this).parent().parent().remove();           
            
        });
    }); 

    function genWotLoop(j) {       
        var woType = document.getElementById("woType"+j).value;       
        var arr = document.getElementsByClassName('woType'); 
        var substr = ['Normal','Main'];
        for(var i=0;i<arr.length;i++){
            if(j!=i){
                var $el = $("#woType"+i);
                $el.empty(); // remove old options
                $.each(substr , function(index, val) {
                    $el.append($("<option></option>")
                      .attr("value", index).text(val));
                });
                $(".js-example-basic-single").select2();
            }
        }    
    }
</script>
@stop