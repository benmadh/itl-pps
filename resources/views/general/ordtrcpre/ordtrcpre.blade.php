@extends('templates.admin.layout')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-11 col-sm-11 col-xs-11">
                            <h2>{{ $title }}</h2>
                        </div> 
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2><a href="{{route('dashboard.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a>
                            </h2>  
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
                        <div class ="col-md-12">
                            <div class="x_panel"> 
                                <div class="form-group">             
                                    <label class="control-label col-md-2 col-sm-2 col-xs-2" for="workorder_no">Work Order No <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        <input type="text" value="{{ Request::old('workorder_no') ?: '' }}" id="workorder_no" name="workorder_no" class="form-control col-md-9 col-xs-9"  >
                                        <span class="text-danger"><strong id="workorder_no-error"></strong></span>
                                    </div>          
                                </div> 
                            </div>                   
                        </div>  
                    </form> 

                    <div id ="page_content"></div>
                    <div class="alert alert-danger" style="display: none" id="alert" name="alert">
                        <h2>Warning Notification</h2></br>
                    </div>
                    <div class ="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Transaction List</h3>
                            </div>

                            <div class="panel-body">
                                <table class="table table-bordered" >
                                    <thead>
                                        <th>Work Order #</th>
                                        <th>WO Department</th>
                                        <th>Scan Date & Time </th>
                                        <th>Scan Department</th>
                                        <th>Tracking Description</th>        
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
</div>
@stop
@section ('js')
<script> 

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
                typingTimer = setTimeout(getWoList, doneTypingInterval);
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

    function getWoList() {
        var formData = $("#formData").serialize();
        clearErrorBox('workorder_no');     
        $.ajax({
            url:'{{ route('ordtrcpre.store') }}',
            type:'POST',
            data:formData,
            success:function(data) {                
                if(data.errors) {
                    if(data.errors.workorder_no){
                        addErrorBox('workorder_no', data.errors.workorder_no[0]);
                        $("#workorder_no").focus().select(); 
                    }
                }
                if(data.success) { 
                    clearErrorBox('workorder_no');                                     
                    getEmployeeTrnList();
                    getWoErrorList();
                    $("#workorder_no").val('');
                    document.getElementById("workorder_no").select();
                    $("#page_content").prepend( '<div id="ajaxMsg" class="alert alert-success" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data.success+'</div>' );

                    setInterval(function(){ 
                        $("#ajaxMsg").remove();
                    }, 1000);
                    $('#workorder_no').focus();
                }
            },
        });      
    } 

    function getEmployeeTrnList() {
        $("#data").empty();        
        var workorder_no = document.getElementById("workorder_no").value;       
        $.ajax({
            url:'{{ url('getWoList') }}/'+workorder_no,
            type:'GET',
            workorder_no:workorder_no,
            success:function(data) {
                $.each(data, function(index, val){
                    $("#data").append('<tr>');  
                    $("#data").append('<td>'+val.workorder_no+'</td>');
                    $("#data").append('<td>'+val.dep_name+'</td>');
                    $("#data").append('<td>'+val.created_at+'</td>');
                    $("#data").append('<td>'+val.tracking_dep+'</td>');
                    $("#data").append('<td>'+val.tracking_des+'</td>');
                    $("#data").append('</tr>');
                });
            },
        });
    }

    function getWoErrorList() {
        $("#dataError").empty();        
        var workorder_no = document.getElementById("workorder_no").value;  

        $.get("http://192.168.2.18/itlpps/public/getScanErrorList/"+workorder_no, function(data1){
            var error_msg =data1[0]['error_msg'];
            var errorMsgLenght =data1[0]['error_msg'].length;
            $("#alert").empty();
            $('#alert').hide();
            if(errorMsgLenght!=0){
                $('#alert').show(); 
                $.each(error_msg, function(index1, val1){  
                    $("#alert").append('***'+val1+'</br>');
                });                   
            }else{               
                $('#alert').hide();               
            }

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