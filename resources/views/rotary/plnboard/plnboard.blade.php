@extends('templates.admin.layout')

@section('content')
<div class="">    
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class ="col-md-4">
                             <h2>{{$title}}</h2>

                        </div>
                        <div class ="col-md-2">
                            <p style="font-size:18px; color:#FF0000;font-weight: bold;">Short Delivery</p>
                        </div>
                        <div class ="col-md-2">
                            <p style="font-size:18px; color:#ebe4e4;font-weight: bold;">Normal Order</p>
                        </div>
                        <div class ="col-md-2">
                            <p style="font-size:18px; color:#efc3dc;font-weight: bold;">Rapid Order</p>
                        </div>
                        <div class ="col-md-2">
                            <p style="font-size:18px; color:#72baaf;font-weight: bold;">Sample Order</p>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">                        
                    <form method="post" action="#" data-parsley-validate class="form-horizontal form-label-left"  id = "formData">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class ="col-md-12">
                                <div class="x_panel">
                                    <div class="tableFixHead" style="height: 600px; overflow: auto;">
                                        <table id="table" class="table table-bordered table-condensed">                                           
                                            <thead>
                                                <tr>
                                                    <th id="dates" style="text-align: left; min-width: 100px !important; background-color: #d9dbdf">Dates</th>
                                                    <th style="text-align: left; min-width: 70px !important; background-color: #d9dbdf">Shift</th>  
                                                    @foreach($machinesHeader as $row )
                                                        <th style="text-align: left; background-color: #d9dbdf">{{$row}}</th>
                                                    @endforeach 
                                                                                              
                                                </tr>                                                
                                            </thead>
                                            <tfoot>
                                                <tr>                          
                                                    <th style="text-align: left; min-width: 100px !important; background-color: #d9dbdf">Dates</th>
                                                    <th style="text-align: left; min-width: 70px !important; background-color: #d9dbdf">Shift</th>  
                                                    @foreach($machinesHeader as $row )
                                                        <th style="text-align: left; background-color: #d9dbdf">{{$row}}</th>
                                                    @endforeach                                                 
                                                </tr>
                                            </tfoot>
                                            <tbody id="tablecontents" >
                                                @if (count($dates_array))                                
                                                    @foreach($dates_array as $key => $value)
                                                        <tr>                                                            
                                                            @if($value["day_typ"] == 'NOR')
                                                                @if($value["shift"] == 'D')
                                                                    <th style="text-align: left; background-color: #FFFFFF"> {{$value["pl_date"]}} ({{$value["short_date"]}})</th>
                                                                    <th style="text-align: left; background-color: #FFFFFF"> {{$value["shift"]}}</th>
                                                                    @foreach($machinesHeader as $index => $val)
                                                                        <td style="text-align: left; background-color: #FFFFFF">                                                                            
                                                                                @if(!(empty($dates_array[$key]["machine_id"][$index])))
                                                                                    @foreach($dates_array[$key]["machine_id"][$index] as $woParentKey => $woDes)    
                                                                                        @if(substr($woParentKey, 0, 7)=="psd_pre") 
                                                                                            <span id="{{$woParentKey}}" style="margin-bottom: 7px !important; background-color: #c5efba !important;font-size: 15px !important;min-width: 340px !important; height: 25px !important; display: inline-block !important"  class="badge badge-dark"> <font style="color: #000000 !important">{{$woDes}}</font></span>           
                                                                                        @endif
                                                                                    @endforeach
                                                                                    @foreach($dates_array[$key]["machine_id"][$index] as $woKey => $woDes)        
                                                                                        @if(substr($woKey, 0, 7)!="psd_pre")
                                                                                            <span style="margin-bottom: 7px !important; background-color:{{$dates_array[$key]['wo_colour_id'][$index][$woKey]}};font-size: 12px !important;text-align: left !important; min-width: 340px !important; display: inline-block !important"  class="event label" id="{{ $woKey }}" draggable="true"> <font style="color: #000000">{{$woDes}} <br><br>
                                                                                                @foreach($dates_array[$key]["wo_list_arr"][$index][$woKey] as $wolArr)
                                                                                                    {{$wolArr}} <br>
                                                                                                @endforeach
                                                                                            </font></span>
                                                                                        @endif
                                                                                    @endforeach                                                                                  
                                                                                @endif
                                                                           
                                                                        </td>
                                                                    @endforeach
                                                                @endif
                                                                @if($value["shift"] == 'N')
                                                                    <th style="text-align: left; background-color: #f9f9f9"> {{$value["pl_date"]}} ({{$value["short_date"]}})</th>
                                                                    <th style="text-align: left; background-color: #f9f9f9"> {{$value["shift"]}}</th>

                                                                    @foreach($machinesHeader as $index => $val)
                                                                        <td style="text-align: left; background-color: #f9f9f9">
                                                                            
                                                                                @if(!(empty($dates_array[$key]["machine_id"][$index])))
                                                                                    @foreach($dates_array[$key]["machine_id"][$index] as $woParentKey => $woDes)   
                                                                                        @if(substr($woParentKey, 0, 7)=="psn_pre") 
                                                                                            
                                                                                            <span id="{{$woParentKey}}" style="margin-bottom: 7px !important; background-color: #c5efba !important;font-size: 15px !important;min-width: 340px !important; height: 25px !important; display: inline-block !important"  class="badge badge-dark"> <font style="color: #000000 !important">{{$woDes}}</font></span>           
                                                                                        @endif
                                                                                    @endforeach
                                                                                    @foreach($dates_array[$key]["machine_id"][$index] as $woKey => $woDes)   
                                                                                        @if(substr($woKey, 0, 7)!="psn_pre")             
                                                                                            <span style="margin-bottom: 7px !important; background-color:{{$dates_array[$key]['wo_colour_id'][$index][$woKey]}};font-size: 12px !important;text-align: left !important; min-width: 340px !important; display: inline-block !important"  class="event label" id="{{ $woKey }}" draggable="true"> <font style="color: #000000">{{$woDes}} <br><br>
                                                                                                @foreach($dates_array[$key]["wo_list_arr"][$index][$woKey] as $wolArr)
                                                                                                    {{$wolArr}} <br>
                                                                                                @endforeach
                                                                                            </font></span>
                                                                                        @endif
                                                                                    @endforeach           
                                                                                @endif
                                                                            
                                                                        </td>
                                                                    @endforeach
                                                                @endif
                                                            @else
                                                                <th style="text-align: left; background-color: {{$value["day_type_clr"]}}"> {{$value["pl_date"]}} ({{$value["short_date"]}})</th>
                                                                <th style="text-align: left; background-color: {{$value["day_type_clr"]}}"> {{$value["shift"]}}</th>

                                                                @if($value["shift"] == 'D')                                                                   
                                                                    @foreach($machinesHeader as $index => $val)
                                                                        <td style="text-align: left; background-color: {{$value["day_type_clr"]}}">
                                                                            
                                                                                @if(!(empty($dates_array[$key]["machine_id"][$index])))
                                                                                    @foreach($dates_array[$key]["machine_id"][$index] as $woParentKey => $woDes)   
                                                                                        @if(substr($woParentKey, 0, 7)=="psd_pre")
                                                                                            <span id="{{$woParentKey}}" style="margin-bottom: 7px !important; background-color: #c5efba !important;font-size: 15px !important;min-width: 340px !important; height: 25px !important; display: inline-block !important" class="badge badge-dark"> <font style="color: #000000 !important">{{$woDes}}</font></span>         
                                                                                        @endif
                                                                                    @endforeach
                                                                                    @foreach($dates_array[$key]["machine_id"][$index] as $woKey => $woDes)   
                                                                                        @if(substr($woKey, 0, 7)!="psd_pre")             
                                                                                            <span style="margin-bottom: 7px !important; background-color:{{$dates_array[$key]['wo_colour_id'][$index][$woKey]}};font-size: 12px !important;text-align: left !important; min-width: 340px !important; display: inline-block !important"  class="event label" id="{{ $woKey }}" draggable="true"> <font style="color: #000000">{{$woDes}} <br><br>
                                                                                                @foreach($dates_array[$key]["wo_list_arr"][$index][$woKey] as $wolArr)
                                                                                                    {{$wolArr}} <br>
                                                                                                @endforeach
                                                                                            </font></span>
                                                                                        @endif
                                                                                    @endforeach
                                                                                
                                                                                @endif
                                                                            
                                                                        </td>
                                                                    @endforeach
                                                                @endif
                                                                @if($value["shift"] == 'N')
                                                                    @foreach($machinesHeader as $index => $val)
                                                                        <td style="text-align: left; background-color: {{$value["day_type_clr"]}}">
                                                                            
                                                                                @if(!(empty($dates_array[$key]["machine_id"][$index])))
                                                                                    @foreach($dates_array[$key]["machine_id"][$index] as $woParentKey => $woDes)   
                                                                                        @if(substr($woParentKey, 0, 7)=="psn_pre")
                                                                                                
                                                                                            <span id="{{$woParentKey}}" style="margin-bottom: 7px !important; background-color: #c5efba !important;font-size: 15px !important;min-width: 340px !important; height: 25px !important; display: inline-block !important"  class="badge badge-dark"> <font style="color: #000000 !important">{{$woDes}}</font></span>          
                                                                                        @endif
                                                                                    @endforeach
                                                                                    @foreach($dates_array[$key]["machine_id"][$index] as $woKey => $woDes)   
                                                                                        @if(substr($woKey, 0, 7)!="psn_pre")             
                                                                                            <span style="margin-bottom: 7px !important; background-color:{{$dates_array[$key]['wo_colour_id'][$index][$woKey]}};font-size: 12px !important;text-align: left !important; min-width: 340px !important; display: inline-block !important"  class="event label" id="{{ $woKey }}" draggable="true"> <font style="color: #000000">{{$woDes}} <br><br>
                                                                                                @foreach($dates_array[$key]["wo_list_arr"][$index][$woKey] as $wolArr)
                                                                                                    {{$wolArr}} <br>
                                                                                                @endforeach
                                                                                            </font></span>
                                                                                        @endif
                                                                                    @endforeach
                                                                                    
                                                                                @endif
                                                                           
                                                                        </td>
                                                                    @endforeach  

                                                                @endif
                                                            @endif          
                                                        </tr>
                                                        
                                                            
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>                   
                            </div>                              
                        </div>  
                    </form>       
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class ="col-md-6">
                    <div class="panel panel-info">
                        <input type="hidden" name="monthlytransactionfiles_id" value="" disabled="" id="monthlytransactionfiles_id">
                        <div class="panel-heading">
                            <h3 class="panel-title">Search Work Order #</h3>
                        </div>                                        

                        <div class="panel-body">
                            <div class="form-group">             
                                <label class="control-label col-md-4 col-sm-4 col-xs-4" for="workorderno">Work Order Number 
                                </label>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <input type="text" value="{{ Request::old('workorderno') ?: '' }}" id="workorderno" name="workorderno" class="form-control col-md-4 col-xs-4">                                    
                                </div>                               
                                <button type="button" class="btn btn-success" id ="search">Search</button>
                            </div>                               
                        </div>                                    
                    </div> 
                </div>
                <div class ="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Search Results</h3>
                        </div>

                        <div class="panel-body">
                            <table class="table table-bordered" >
                                <thead>                                            
                                    <th>Machine</th>
                                    <th>Plan Date</th>
                                    <th>Shift</th>
                                    <th>Quantity</th>
                                    <th>Delivery Date</th>
                                </thead>
                                <tbody id ="data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>  
            </div>

        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="pleaseWaitDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h1>Processing...</h1>
      </div>
      <div class="modal-body">
        <div class="progress">
          <div id="progressbar" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
         </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
@section ('js')
<script>  
    var $th = $('.tableFixHead').find('thead th')
    $('.tableFixHead').on('scroll', function() {
      $th.css('transform', 'translateY('+ this.scrollTop +'px)');
    });

   var $tbodyth = $('.tableFixHead').find('tbody th')
    $('.tableFixHead').on('scroll', function() {
      $tbodyth.css('transform', 'translateX('+ this.scrollLeft +'px)');
    });

    $(".js-example-basic-single").select2();
    
    $('body').on('click', '#btn_process', function() { 
        var pleaseWait = $('#pleaseWaitDialog'); 
        pleaseWait.modal('show');
        i=0;
        var refreshId = setInterval(function() {
            $('#progressbar').width(10*i++);
        }, 1000);
    });


    $(document).ready(function() {
        $('.event').on("dragstart", function(event) {
            var dt = event.originalEvent.dataTransfer;
            dt.setData('Text', $(this).attr('id'));
            dt.setData('drag_first_span_id', $(this).parent().children().attr('id')); 
           
        });

        $('table td').on("dragenter dragover drop", function(event) {
            event.preventDefault();
           
            if (event.type === 'drop') {
                var planningboard_id = event.originalEvent.dataTransfer.getData('Text', $(this).attr('id'));
                var drag_first_span_id = event.originalEvent.dataTransfer.getData('drag_first_span_id', $(this).parent().parent().children().children().attr('id'));
                var drop_first_span_id = event.currentTarget.firstElementChild.id;
                de = $('#' + planningboard_id).detach();
                de.appendTo($(this)); 
                $('#' + planningboard_id).css("border", "2px solid blue");

                var col = $(this).parent().children().index($(this));
                var row = $(this).parent().parent().children().index($(this).parent());  
                var gridTable = document.getElementById("table");            
                var dropDate=gridTable.rows[row+1].cells[0].innerText;
                var dropShift=gridTable.rows[row+1].cells[1].innerText;
                var dropMachine=gridTable.rows[0].cells[col].innerText;
                var dropTdData=gridTable.rows[row+1].cells[col].innerText;            
                var dropDate = dropDate.substring(0, 10);
                //var dropMachine =1;
                var formData = $("#formData").serialize();
                console.log(dropDate);
                console.log(dropShift);
                console.log(dropMachine);
                console.log(planningboard_id);
                $.ajax({
                    url:'{{ url('plnBoard/change_wo_date') }}/'+dropDate+"/"+dropShift+"/"+dropMachine+"/"+planningboard_id,
                    type:'GET',
                    data: { 
                        dropDate: dropDate,
                        dropShift: dropShift,
                        dropMachine: dropMachine,
                        planningboard_id: planningboard_id,
                        drag_first_span_id:drag_first_span_id,
                        drop_first_span_id:drop_first_span_id
                    },
                    success:function(data) { 
                        $("#"+drag_first_span_id).text('');
                        $("#"+drop_first_span_id).text('');
                        $.each(data, function(index, val){                            
                            if(index==0){                                
                                $("#"+drag_first_span_id).text(val.drag_pr);
                                $("#"+drag_first_span_id).css('color','black');                                
                            }
                            if(index==1){                                 
                                $("#"+drop_first_span_id).text(val.drop_pr); 
                                $("#"+drop_first_span_id).css('color','black');
                                                                                         
                            }                            
                        });                       
                    },
                });
                
            };
        });

        $('body').on('click', '#search', function() {
            $("#data").empty();
            var workorderno = document.getElementById("workorderno").value;            
            $.ajax({
                url:'{{ url('getWoPlanListRotary') }}/'+workorderno,
                type:'GET',
                workorderno:workorderno,
                success:function(data) {                   
                    $.each(data, function(index, val){
                        $("#data").append('<tr>');                        
                        $("#data").append('<td>'+val.machin_number+'</td>');
                        $("#data").append('<td>'+val.pln_date+'</td>');
                        $("#data").append('<td>'+val.pln_shift+'</td>');
                        $("#data").append('<td>'+val.pln_qty+'</td>');
                        $("#data").append('<td>'+val.deliverydate+'</td>');
                        $("#data").append('</tr>');
                    });
                },
            });
        });
    });

</script>
@stop