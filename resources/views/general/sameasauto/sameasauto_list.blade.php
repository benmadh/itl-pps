@extends('templates.admin.layout')

@section('content')
<div class="">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Automation of Same as Details</h2>
                    <br />
                    <div class="clearfix"></div>
                </div>
                <br />
                <br />             
                <div class="form-group">                           
                    <form method="post" action="{{ route('sameasauto.search') }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}                        

                        <div class="row">
                            <div class ="col-md-6">
                                <div class="form-group{{ $errors->has('workorderno') ? ' has-error' : '' }}">             
                                    <label class="control-label col-md-4 col-sm-4 col-xs-4" for="workorderno">Main Work Order No 
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        <input type="text" value="{{ $searchingVals["workorderno"] }}" id="workorderno" name="workorderno" class="form-control col-md-8 col-xs-8">
                                        @if ($errors->has('workorderno'))
                                            <span class="help-block">{{ $errors->first('workorderno') }}</span>
                                        @endif
                                    </div>          
                                </div> 
                            </div>                                               
                        </div>
                            
                        <br />
                        <div class="box-footer">
                        <button type="submit" class="btn btn-primary">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            Search
                        </button>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div> 
                                    @if(!empty($data_array)) 
                                        @if($data_array[0]["errorsMsg"]=="Y")                               
                                            <table id="customers" class="table table-striped table-bordered">
                                                <tr>
                                                    <th colspan="7" style="text-align: left; background-color:Tomato; font-size: 20px; " ><font color="#FFFFFF">{{ $data_array[0]["rcStatus"] }}</font></th>
                                                </tr>                                                                       
                                            </table>
                                        @else
                                             <table id="customers" class="table table-striped table-bordered">
                                                <tr>
                                                    <th colspan="7" style="text-align: left; background-color:#009517; font-size: 20px; " ><font color="#FFFFFF">{{ $data_array[0]["rcStatus"] }}</font></th>
                                                </tr>                                                                       
                                            </table>
                                        @endif                  
                                    @endif
                                </div>                        
                            </div>
                        </div> 
                    </form>       
                </div>
                
                <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr> 
                                <th>@lang('general.common.action')</th>                              
                                <th>Depertment</th>
                                <th>Order Type</th>                                
                                <th>Work Order No</th>
                                <th>Main Work Order No</th>
                                <th>Po Number</th>
                                <th>Po Quantity</th>
                                <th>Delivery Date</th>                                
                                <th>Customer</th>
                                <th>Chain</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>                               
                                <th>@lang('reference.updates')</th>                              
                                <th>Depertment</th>
                                <th>Order Type</th>                                
                                <th>Work Order No</th>
                                <th>Main Work Order No</th>
                                <th>Po Number</th>
                                <th>Po Quantity</th>
                                <th>Delivery Date</th>                                
                                <th>Customer</th>
                                <th>Chain</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @if(!(empty($workorders)))                              
                                @foreach($workorders as $row)
                                    <tr>
                                        <td>                                   
                                            <a href="{{ route('sameasauto.edit_sameas_auto', ['id' => $row->main_workorder_id]) }}" class="btn btn-success btn-xs"><i class="fa fa-pencil" title="Allocate Work Orders"></i> </a>
                                            <a href="{{ route('sameasauto.print_sameas_auto', ['id' => $row->main_workorder_id]) }}" class="btn btn-info btn-xs"><i class="fa fa-print" title="Allocate Work Orders"></i> </a> 
                                        </td>                                      
                                        <td>{{$row->dep_name}}</td> 
                                        <td style="background-color: {{$row->colorpicker_id}}"><h4>{{$row->ordertype_name}}</h4></td>                               
                                        <td>{{$row->workorder_no}}</td>
                                        <td>{{$row->main_workorder_no}}</td>
                                        <td>{{$row->po_number}}</td>
                                        <td>{{$row->wo_quantity}}</td>
                                        <td>{{$row->delivery_date}}</td> 
                                        <td>{{$row->cus_name}}</td>
                                        <td>{{$row->chain_name}}</td> 

                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
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
</script>
@stop