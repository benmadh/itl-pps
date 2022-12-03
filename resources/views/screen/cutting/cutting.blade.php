@extends('templates.admin.layout')

@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-11 col-sm-11 col-xs-11">
                            <h1>{{ $title }}</h1>                    
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2> <a href="{{route('dbScreen.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group"> 
                    <div class="alert alert-danger" style="display: none" id="alert" name="alert">
                        ERROR 
                    </div> 
                    <form data-parsley-validate class="form-horizontal form-label-left" id = "formData">
                        {{ csrf_field() }}   
                        <div id ="page_content"></div>  
                        <div class="row">                       
                            <div class ="col-md-6">
                                <div class="x_panel">                                 
                                    <div class="form-group"> 
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="from">Date <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <div class="input-group date">
                                                <input type="text" value="{{ $from }}" id="from" name="from" class="form-control col-md-4 col-xs-4" readonly>
                                                <span class="text-danger"><strong id="from-error"></strong></span>
                                                <div class="input-group-addon">
                                                      <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group"> 
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="shift">Shift <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <select class="form-control" id="shift" name="shift">
                                                @if(count($shiftList))
                                                    @foreach($shiftList as $row)                                                   
                                                        <option value="{{$row}}" {{ old('shift') == $row ? 'selected' : '' }} >{{$row}}</option>
                                                    @endforeach
                                                @endif
                                            </select> 
                                            <span class="text-danger"><strong id="shift-error"></strong></span>
                                        </div>
                                    </div>

                                    <div class="form-group"> 
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="machines_id">Machines No <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <select class="js-example-basic-single form-control" id="machines_id" name="machines_id">
                                                @if(count($machines))
                                                <option value=""></option>
                                                    @foreach($machines as $row)                                                     
                                                        <option value="{{$row->id}}" {{ old('machines_id') == $row->id ? 'selected' : '' }} >{{$row->machin_number}} - {{$row->asset_number}}</option>
                                                    @endforeach
                                                @endif
                                            </select>  
                                            <span class="text-danger"><strong id="machines_id-error"></strong></span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group"> 
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="employees_id">EPF No <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <select class="js-example-basic-single form-control" id="employees_id" name="employees_id" onchange="getEmpPhoto()">
                                                @if(count($employees))
                                                <option value=""></option>
                                                    @foreach($employees as $row)                                                    
                                                         <option value="{{$row->id}}" {{ old('employees_id') == $row->id ? 'selected' : '' }} >{{$row->epf_no}} - {{$row->full_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>  
                                            <span class="text-danger"><strong id="employees_id-error"></strong></span>
                                        </div>
                                    </div>

                                    <div class="form-group"> 
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="scan_wo">Scan Main WO <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <input type="text" value="{{ Request::old('scan_wo') ?: '' }}" id="scan_wo" name="scan_wo" class="form-control col-md-7 col-xs-12">
                                            <span class="text-danger"><strong id="scan_wo-error"></strong></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="scan_wo">Select WO 
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <select class="selectpicker form-control" data-live-search="true" multiple data-actions-box="true" data-size="10" name="wo_ids[]" id="wo_ids" title="Choose one or many of the following...">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="scan_wo">Size 
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <select class="selectpicker form-control" data-live-search="true" multiple data-actions-box="true" data-size="10" name="size_id[]" id="size_id" title="Choose one or many of the following...">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                   

                                    <div class="form-group"> 
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="workorder_no">Work Order No <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <input type="text" value="{{ Request::old('workorder_no') ?: '' }}" id="workorder_no" name="workorder_no" class="form-control col-md-7 col-xs-12">
                                            <span class="text-danger"><strong id="workorder_no-error"></strong></span>
                                        </div>
                                    </div> 
                                </div>                   
                            </div>

                            <div class ="col-md-2">
                                <div class="x_panel">
                                    <div class="form-group" id="empPhot">
                                        <img src="{{ url('/') }}/upload/images/employee/default.jpg" id="img" width="175px" height="195px">

                                    </div>
                                </div>
                            </div> 
                        </div>                               
                    </form>       
                </div>                             
                <div class ="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Data List</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered" >
                                <thead>
                                    <tr>
                                        <th></th> 
                                        <th>Date</th>                               
                                        <th>Shift</th>
                                        <th>WO #</th>
                                        <th>Size</th>                                                            
                                        <th>Size Qty</th>
                                        <th>Print Qty</th>
                                        <th>Cumulative Qty</th>
                                        <th>Balance Qty</th>                                                                           
                                        <th>Operator</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th> 
                                        <th>Date</th>                               
                                        <th>Shift</th>
                                        <th>WO #</th> 
                                        <th>Size</th>                                                          
                                        <th>Size Qty</th>
                                        <th>Print Qty</th>
                                        <th>Cumulative Qty</th>
                                        <th>Balance Qty</th>                                                                              
                                        <th>Operator</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
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
@stop
@section ('js')

<script>    
    $(".js-example-basic-single").select2();   
    $("#scan_wo").focus().select();
    $('#div1').hide();
     var block = {
      'ch1': {
          'key':'/',
          'keycode':'191'
      },
      'ch2': {
          'key':':',
          'keycode':'186'
      },
      'ch3': {
          'key':'=',
          'keycode':'187'
      }
    }

    var typingTimer;              
    var doneTypingInterval = 1000; 
    $('#workorder_no').on('keyup',function(e){
        e.preventDefault();
        var res = checkChar(e, $(this));        
        var code = e.keyCode || e.which;        
        if (res !== undefined) { 
            clearTimeout(typingTimer);
            if ($(this).val()) {
                typingTimer = setTimeout(getCuttingLstRotary, doneTypingInterval);
            }
        };        
    });

    $('#scan_wo').on('keyup',function(e){
        e.preventDefault();
        var res = checkChar(e, $(this));        
        var code = e.keyCode || e.which;        
        if (res !== undefined) { 
            clearTimeout(typingTimer);
            if ($(this).val()) {
                typingTimer = setTimeout(getSizeBrackDown, doneTypingInterval);
            }
        };        
    });

    function checkChar(e, $this){
        if (e.shiftKey) {
            return
        };
        var txt  = $this.val(),
        flag = true;
        for(var i=0; i<txt.length; i++) {
            $.each(block,function(k,v){
                if (txt[i] === v.key) {
                    $this.val(txt.slice(0,-1));
                    flag = false;
                    return false;
                }
            });
        }
      return flag;
    }

    $(".js-example-basic-single").select2();   
    
    function getSizeBrackDown() {         
        var scan_wo = document.getElementById("scan_wo").value;         
        $.get('{{ url('getSizeBrackDown') }}/'+scan_wo, function(data){            
            if(data.length==0){
                $("#ajaxMsg").remove();
                $("#page_content").prepend( '<div id="ajaxMsg" class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+'Invalide WO # '+scan_wo+'</div>' );                
                addErrorBox('scan_wo', 'Invalide WO #');
                $("#scan_wo").focus().select();
            }else{
                $("#ajaxMsg").remove();
                clearErrorBox('scan_wo');
                $("#size_id").empty();                
                var options = '';
                $.each(data, function(index, val){                     
                    options += '<option value="' + val + ' select">' + val +'</option>';
                }); 
                $("#size_id").selectpicker('refresh').empty().append(options).selectpicker('refresh').trigger('change');             
                $("#workorder_no").focus().select();
            }            
        }); 

        $.get('{{ url('getWoLst') }}/'+scan_wo, function(data){            
            if(data.length==0){
                $("#ajaxMsg").remove();
                $("#page_content").prepend( '<div id="ajaxMsg" class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+'Invalide WO # '+scan_wo+'</div>' );                
                addErrorBox('scan_wo', 'Invalide WO #');
                $("#scan_wo").focus().select();
            }else{
                $("#ajaxMsg").remove();
                clearErrorBox('scan_wo');
                $("#wo_ids").empty();                
                var options = '';
                $.each(data, function(index, val){                     
                    options += '<option value="' + val + ' select">' + val +'</option>';
                }); 
                $("#wo_ids").selectpicker('refresh').empty().append(options).selectpicker('refresh').trigger('change');             
                $("#workorder_no").focus().select();
            }            
        });     
    }

    function getCuttingLstRotary() {        
        var formData = $("#formData").serialize();
        clearErrorBox('workorder_no');
        clearErrorBox('scan_wo');
        clearErrorBox('from'); 
        clearErrorBox('shift');         
        clearErrorBox('employees_id');                     
        $.ajax({
            url:'{{ route('cuttingScreen.store') }}',
            type:'POST',
            data:formData,
            success:function(data) {   

                if(data.errors) {
                    $("#ajaxMsg").remove();                    
                    if(data.errors.workorder_no){
                        $("#page_content").prepend( '<div id="ajaxMsg" class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data.errors.workorder_no+'</div>' );                        
                    }
                    if(data.errors.from){
                        addErrorBox('from', data.errors.from[0]);                        
                    }
                    if(data.errors.shift){
                        addErrorBox('shift', data.errors.shift[0]);                        
                    }                   
                    if(data.errors.employees_id){
                        addErrorBox('employees_id', data.errors.employees_id[0]);                        
                    }                                    
                  
                    $("#scan_wo").focus().select();  
                }
                if(data.success) { 
                    $("#ajaxMsg").remove();
                    clearErrorBox('workorder_no');
                    clearErrorBox('scan_wo');
                    clearErrorBox('from'); 
                    clearErrorBox('shift');                    
                    clearErrorBox('employees_id');                    
                    getEmployeeTrnList();
                    $("#workorder_no").val('');
                    $("#quantity").val('');
                    $("#workorder_no").focus().select();
                    $("#page_content").prepend( '<div id="ajaxMsg" class="alert alert-success" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data.success+'</div>' );
                    $('#select_workorder').bootstrapToggle('off')
                    $("#scan_wo").focus().select();
               
                }
            },
        });      
    } 

    function getEmployeeTrnList() {
        $("#data").empty();        
        var workorder_no = document.getElementById("workorder_no").value;       
        $.ajax({           
            url:'{{ url('getCuttingDetailsScreen') }}',           
            type:'GET',
            success:function(data) {            
                $.each(data, function(index, val){
                    $("#data").append('<tr>');   
                    $("#data").append('<td></td>');                
                    $("#data").append('<td>'+val["date"]+'</td>');
                    $("#data").append('<td>'+val["shift"]+'</td>');
                    $("#data").append('<td>'+val["workorder_no"]+'</td>');
                    $("#data").append('<td>'+val["size_no"]+'</td>');
                    $("#data").append('<td>'+val["size_qty"]+'</td>');
                    $("#data").append('<td>'+val["prd_qty"]+'</td>');
                    $("#data").append('<td>'+val["cumulative_qty"]+'</td>');
                    $("#data").append('<td>'+val["balance_qty"]+'</td>'); 
                    $("#data").append('<td>'+val["epf_des"]+'</td>');                
                    $("#data").append('</tr>');
                });
            },
        });
    }

    function getEmpPhoto() {
        $("#empPhoto").empty();
        var employee_id = document.getElementById("employees_id").value;

        $.ajax({
            url:'{{ url('getEmpPhoto') }}/'+employee_id,
            type:'GET',
            employee_id:employees_id,
            success:function(data) {                
                var photo =data[0]['photo'];
                var source = "{{ url('/') }}/upload/images/employee/"+photo;
                $("#img").attr("src",source); 
            },
        });
    }

    function clearErrorBox(id) {
        $( '#'+id+'-error' ).html( "" );
        $( '#'+id+'-error' ).parent().parent().parent().removeClass("has-error");
    }

    function addErrorBox(id, msg) {
        $( '#'+id+'-error' ).html( msg );
        $( '#'+id+'-error' ).parent().parent().parent().addClass("has-error");
    } 
</script>
@stop