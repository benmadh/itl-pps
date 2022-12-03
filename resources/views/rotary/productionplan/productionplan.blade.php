@extends('templates.admin.layout')

@section('content')
<div class="">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Production Plan - Rotary</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">                        
                    <form method="post" action="{{ route('prdPlnRotary.process') }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}
                        <div class ="col-md-6">
                            <div class="x_panel"> 
                                <div class="form-group{{ $errors->has('machine_id') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3" for="machine_id">Machine 
                                    </label>
                                    <div class="col-md-7 col-sm-7col-xs-7">
                                        <select class="js-example-basic-single form-control" id="machine_id" name="machine_id">
                                            @if(count($machines))
                                                @foreach($machines as $row)
                                                     <option value="{{$row->id}}" {{$row->id == Request::old('machine_id') ? 'selected="selected"' : ''}}>{{$row->machin_number}}</option>
                                                @endforeach
                                            @endif
                                        </select>                                           
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3" for="from">From Date
                                    </label>
                                    <div class="col-md-7 col-sm-7 col-xs-7">
                                        <div class="input-group date">
                                             <input type="text" value="{{ $searchingVals["from"] }}" id="from" name="from" class="form-control col-md-4 col-xs-4">
                                              @if ($errors->has('from'))
                                                  <span class="help-block">{{ $errors->first('from') }}</span>
                                              @endif
                                            <div class="input-group-addon">
                                                  <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3" for="to">To Date
                                    </label>
                                    <div class="col-md-7 col-sm-7 col-xs-7">
                                        <div class="input-group date">
                                             <input type="text" value="{{ $searchingVals["to"] }}" id="to" name="to" class="form-control col-md-4 col-xs-4">
                                              @if ($errors->has('to'))
                                                  <span class="help-block">{{ $errors->first('to') }}</span>
                                              @endif
                                            <div class="input-group-addon">
                                                  <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('machine_operator') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3" for="machine_operator">Machine Operator <span class="required">*</span>
                                    </label>
                                    <div class="col-md-7 col-sm-7 col-xs-7">
                                        <input type="text" value="{{ Request::old('machine_operator') ?: '' }}" id="machine_operator" name="machine_operator" class="form-control col-md-7 col-xs-12">
                                        @if ($errors->has('machine_operator'))
                                        <span class="help-block">{{ $errors->first('machine_operator') }}</span>
                                        @endif
                                    </div>
                                </div> 

                                 <br />
                                <div class="box-footer">
                                <button id="btn_process" type="submit" class="btn btn-primary">
                                    <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                                    Print
                                </button>
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
</script>
@stop