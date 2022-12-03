@extends('templates.admin.layout')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Order Tracking Details</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">                           
                    <form method="post" action="{{ route('ordertracking.search') }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class ="col-md-12">
                                <div class="x_panel"> 
                                    <div class ="col-md-4">                                
                                        <div class="form-group{{ $errors->has('wonumber') ? ' has-error' : '' }}">             
                                            <label class="control-label col-md-4 col-sm-4 col-xs-4" for="wonumber">Work Order # 
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <input type="text" value="{{ $searchingVals["wonumber"] }}" id="wonumber" name="wonumber" class="form-control col-md-7 col-xs-7" required>
                                                @if ($errors->has('wonumber'))
                                                    <span class="help-block">{{ $errors->first('wonumber') }}</span>
                                                @endif
                                            </div>          
                                        </div>                                    
                                    </div> 
                                    <div class ="col-md-2"> 
                                        <div class="form-group{{ $errors->has('operator') ? ' has-error' : '' }}">
                                            <label class="control-label col-md-5 col-sm-5 col-xs-5" for="operator">Operator
                                            </label>
                                            <div class="col-md-7 col-sm-7 col-xs-7">
                                                <select class="form-control" id="operator" name="operator">
                                                    @if(count($operatorList))
                                                        @foreach($operatorList as $row)
                                                            <option value="{{$row}}" {{$row == $operator ? 'selected="selected"' : ''}} >{{$row}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @if ($errors->has('operator'))
                                                    <span class="help-block">{{ $errors->first('operator') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class ="col-md-6">                                    
                                        <button type="submit" class="btn btn-primary">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            Search
                                        </button>
                                    </div> 
                                </div>
                            </div>
                        </div>                             
                    </form>       
                </div> 

                @if(!empty($dates_array))
                    @if($dates_array[0]["statuses_id"]==3) 
                        <h3 class="panel-title"><span style="font-weight: bold; color: red; font-size: 25px;">Latest update is : {{$dates_array[0]["last_status"]}} | Scan date : {{$dates_array[0]["last_status_date"]}}</span></h3> 
                        <br/>
                    @else
                      @if($dates_array[0]["last_status"]=="Not Available") 
                        <h3 class="panel-title"><span style="font-weight: bold; color: blue; font-size: 25px;">Latest update is : {{$dates_array[0]["last_status"]}} </span></h3>
                        <br/>
                      @else
                       <h3 class="panel-title"><span style="font-weight: bold; color: blue; font-size: 25px;">Latest update is : {{$dates_array[0]["last_status"]}} | Scan date : {{$dates_array[0]["last_status_date"]}} </span></h3>
                       <br/>
                      @endif  
                     
                    @endif
                @endif               
                @if(!empty($dates_array))
                    @if($dates_array[0]["fldsFlg"]=="Y") 
                        <div class="x_content">
                            <h3 class="panel-title">Work Order Detail</h3>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>                                
                                        <th>WO Number</th>
                                        <th>PO Number</th>                               
                                        <th>Quantity</th>
                                        <th>Order Type</th>
                                        <th>Order Date</th>
                                        <th>Delivery Date</th>
                                        <th>PCU Date</th>
                                        <th>Referance</th>
                                        <th>Department</th>
                                        <th>Customer</th>
                                        <th>Create Date</th>
                                        <th>Delete Date</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>                                
                                        <th>WO Number</th>
                                        <th>PO Number</th>                               
                                        <th>Quantity</th>
                                        <th>Order Type</th>
                                        <th>Order Date</th>
                                        <th>Delivery Date</th>
                                        <th>PCU Date</th>
                                        <th>Referance</th>
                                        <th>Department</th>
                                        <th>Customer</th>
                                        <th>Create Date</th>
                                        <th>Delete Date</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @if (!empty($woHeaderDataList))                             
                                        @foreach($woHeaderDataList as $row)
                                        <tr>                               
                                            <td>{{$row->workorder_no}}</td>
                                            <td>{{$row->po_number}}</td>
                                            <td>{{$row->wo_quantity}}</td>
                                            <td>{{$row->orderstype_name}}</td> 
                                            <td>{{$row->order_date}}</td>
                                            <td>{{$row->delivery_date}}</td>
                                            <td>{{$row->pcu_date}}</td> 
                                            <td>{{$row->references_name}}</td>
                                            <td>{{$row->departments_name}}</td>                                
                                            <td>{{$row->cus_name}}</td>
                                            <td>{{$row->created_at}}</td>
                                            @if($row->deleted_at)
                                                <td style="background-color:#FFE5E0;">{{$row->deleted_at}}</td> 
                                            @else
                                                <td style="background-color:#94FFA4;">{{$row->deleted_at}}</td> 
                                            @endif                                                             
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div> 

                        <div class="x_content">
                            <h3 class="panel-title">Same as Work Order List  (Main Workorder # :
                                @if (!empty($dates_array))                              
                                   {{$dates_array[0]["main_workorder"]}} )
                                @endif    
                            </h3>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>                                
                                        <th>Work Order #</th>
                                        <th class="text-right">Quantity</th>                               
                                        <th class="text-center" >Delivery Date</th>
                                        @if (!empty($dates_array))                             
                                            @foreach($dates_array[0]["header_des"] as $key => $value)
                                                <th class="text-center">{{$value["size_head"]}}</th>
                                            @endforeach
                                        @endif
                                        <th class="text-right">Total Qty</th>
                                    </tr>
                                </thead>
                                
                                 <tfoot>
                                    <tr>                                
                                        <th>Total</th>
                                        @if (!empty($dates_array))                              
                                           <th class="text-right">{{$dates_array[0]["wo_quantity_tot"]}}</th>
                                        @endif 
                                        <th></th>
                                        @if (!empty($dates_array)) 
                                            @foreach($dates_array[0]["size_qty_arr"] as $totKey => $totValue)
                                                <th class="text-right">{{$totValue}}</th>
                                            @endforeach
                                        @endif 
                                    </tr>
                                </tfoot>

                                <tbody> 
                                    @if (!empty($dates_array))                              
                                        @foreach($dates_array[0]["woh_des"] as $wohKey => $wohValue)
                                            <tr> 
                                                <td>{{$wohValue["workorderno"]}}</td>                                        
                                                <td align="right">{{$wohValue["size_tot_qty"]}}</td>
                                                <td align="center">{{$wohValue["deliverydate"]}}</td>
                                                @foreach($wohValue["size_qty"] as $sqKey => $sqValue)
                                                    <td align="right">{{$sqValue["size_qty"]}}</td>
                                                @endforeach
                                                <td align="right">{{$wohValue["wo_quantity"]}}</td>
                                            </tr>
                                        @endforeach
                                    @endif 
                                </tbody>
                            </table>
                        </div> 

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_content">
                                    <h3 class="panel-title">Order Tracking Details</h3>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr> 
                                                <th class="text-center">RNO</th>                               
                                                <th>WO Number</th>
                                                <th>Scan Date</th>                               
                                                <th>Scan Department</th>
                                                <th>Description</th>                              
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th class="text-center">RNO</th>                                 
                                                <th>WO Number</th>
                                                <th>Scan Date</th>                               
                                                <th>Scan Department</th>
                                                <th>Description</th> 
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @if (!empty($orderTrcList))
                                                @php ($i = 1)                             
                                                @foreach($orderTrcList as $otrcrow)
                                                <tr> 
                                                    <td align="center">{{ $i }}</td>                               
                                                    <td>{{$otrcrow->workorder_no}}</td>
                                                    <td>{{$otrcrow->created_at}}</td>
                                                    <td>{{$otrcrow->tracking_dep}}</td> 
                                                    <td>{{$otrcrow->tracking_des}}</td>                  
                                                </tr>
                                                @php ($i++)
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>                            
                        </div>
                    @else
                        <table id="customers" class="table table-striped table-bordered">
                            <tr>
                                <th colspan="7" style="text-align: left; background-color:Tomato; font-size: 20px; " ><font color="#FFFFFF">{{ $dates_array[0]["fldsRcStatus"] }}</font></th>
                            </tr>                                                                       
                        </table>
                    @endif                                
                @endif              
            </div>
        </div>
    </div>
</div>
@stop