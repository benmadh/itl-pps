@extends('templates.admin.layout')

@section('content')
<div class="">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Planning Process - Rotary  <span class="fa fa-hand-o-right" aria-hidden="true"></span> </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">                        
                    <form method="post" action="{{ route('processRotary.process') }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}                        
                        <br /> 
                        <div class="row"> 
                            <div class ="col-md-6">                       
                                <div class="form-group{{ $errors->has('process_id') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3" for="process_id">Process Type</label>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <select class="js-example-basic-single form-control" id="process_id" name="process_id">
                                            @if(count($processList))                                    
                                                @foreach($processList as $row)
                                                     <option value="{{$row}}" {{$row == $searchingVals["process_id"] ? 'selected="selected"' : ''}}>{{$row}}</option>
                                                @endforeach
                                            @endif
                                        </select>                                           
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">                       
                          <div class ="col-md-6">
                              <div class="form-group" id="yes_no">
                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="yes_no">Saturday Working</label>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <select class="js-example-basic-single form-control" id="yes_no" name="yes_no">
                                      @if(count($yesNoList))                                    
                                          @foreach($yesNoList as $row)
                                              <option value="{{$row}}" {{$row ==  $searchingVals["yes_no"] ? 'selected="selected"' : ''}}>{{$row}}</option>
                                          @endforeach
                                      @endif
                                    </select>                                           
                                </div>
                              </div>
                          </div>
                        </div>

                        <div class="row">
                            <div class="box-footer">
                              <button id="btn_process" type="submit" class="btn btn-primary">
                                  <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                                  Process
                              </button>                              
                              <h3 style="color:#009900; font-weight:bold" >{{ $error_des }} </h3>
                            </div> 
                             <input type="text" value="{{ $str_time }}" id="strtime" name="strtime" class="form-control col-md-7 col-xs-7"> 
                             <input type="text" value="{{ $end_time }}" id="end_time" name="end_time" class="form-control col-md-7 col-xs-7">                              
                        </div>
                    </form>       
                </div>  
            </div>
        </div>
    </div>
</div>
@stop