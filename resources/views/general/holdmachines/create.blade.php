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
                            <h2>@lang('holdMachines.create')</h2>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2> <a href="{{route('holdmachines.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('holdmachines.store') }}" data-parsley-validate class="form-horizontal form-label-left">
                        
                        <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="date">@lang('general.common.date') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="input-group date">
                                    <input type="text" value="{{ Request::old('date') ?: $date }}" id="date" name="date" class="form-control col-md-7 col-xs-12">
                                    @if ($errors->has('date'))
                                        <span class="help-block">{{ $errors->first('date') }}</span>
                                    @endif
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div> 

                        <div class="form-group{{ $errors->has('department_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="department_id">@lang('general.common.department') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="js-example-basic-single form-control" id="department_id" name="department_id" onchange="getMachineList()">
                                    @if(count($dataDepts))
                                        <option value=""></option>                                    
                                        @foreach($dataDepts as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    @endif
                                </select> 
                                @if ($errors->has('department_id'))
                                    <span class="help-block">{{ $errors->first('department_id') }}</span>
                                @endif                                           
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('machine_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="machine_id">@lang('general.common.machine') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="form-control" id="machine_id" name="machine_id">
                                    <option value=""></option>                                              
                                </select> 
                                @if ($errors->has('machine_id'))
                                    <span class="help-block">{{ $errors->first('machine_id') }}</span>
                                @endif                                          
                            </div>
                        </div> 

                        <div class="form-group{{ $errors->has('shift') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="shift">@lang('general.common.shift') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="js-example-basic-single form-control" id="shift" name="shift">
                                    @if(count($shiftList))                                                                       
                                        @foreach($shiftList as $row)
                                            <option value="{{$row}}">{{$row}}</option>
                                        @endforeach
                                    @endif
                                </select> 
                                @if ($errors->has('shift'))
                                    <span class="help-block">{{ $errors->first('shift') }}</span>
                                @endif                                            
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mc_hold_reason_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="mc_hold_reason_id">@lang('general.common.reason') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="js-example-basic-single form-control" id="mc_hold_reason_id" name="mc_hold_reason_id" >
                                    @if(count($dataReason))                                                                           
                                        @foreach($dataReason as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    @endif
                                </select>   
                                @if ($errors->has('mc_hold_reason_id'))
                                    <span class="help-block">{{ $errors->first('mc_hold_reason_id') }}</span>
                                @endif                                           
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
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
        });

        function getMachineList() {
            var department_id = document.getElementById("department_id").value;           
            $.get('{{ url('getMachineList') }}/'+department_id, function(data){
                $('#machine_id').empty();
                $('#machine_id').append('<option value=""></option>');            
                $.each(data,function(index, dataObj){                    
                   $('#machine_id').append('<option value="'+dataObj.id+'">'+dataObj.machin_number+'</option>');
                });                
            });
        }

    </script>
@stop