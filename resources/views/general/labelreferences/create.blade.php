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
                            <h2>@lang('labelreferences.create')</h2>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2> <a href="{{route('labelreferences.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('labelreferences.store') }}" data-parsley-validate class="form-horizontal form-label-left">
                        
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">@lang('general.common.name') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('name') ?: '' }}" id="name" name="name" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('name'))
                                    <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">@lang('general.common.description') 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('description') ?: '' }}" id="description" name="description" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('description'))
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('ground_colour') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ground_colour">@lang('general.common.ground_colour') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('ground_colour') ?: '' }}" id="ground_colour" name="ground_colour" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('ground_colour'))
                                    <span class="help-block">{{ $errors->first('ground_colour') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('print_colour') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="print_colour">@lang('general.common.print_colour') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('print_colour') ?: '' }}" id="print_colour" name="print_colour" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('print_colour'))
                                    <span class="help-block">{{ $errors->first('print_colour') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('combo') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="combo">@lang('general.common.combo') 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('combo') ?: '' }}" id="combo" name="combo" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('combo'))
                                    <span class="help-block">{{ $errors->first('combo') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('default_lenght') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="default_lenght">@lang('general.common.default_lenght') 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('default_lenght') ?: '' }}" id="default_lenght" name="default_lenght" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('default_lenght'))
                                    <span class="help-block">{{ $errors->first('default_lenght') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('default_width') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="default_width">@lang('general.common.default_width') 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('default_width') ?: '' }}" id="default_width" name="default_width" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('default_width'))
                                    <span class="help-block">{{ $errors->first('default_width') }}</span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('chain_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="chain_id">@lang('general.common.chain') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="js-example-basic-single form-control" id="chain_id" name="chain_id">
                                    @if(count($chainsData))                                        
                                        @foreach($chainsData as $rowChainsData)
                                              <option value="{{$rowChainsData->id}}">{{$rowChainsData->name}} </option>
                                        @endforeach
                                    @endif
                                </select>  
                                 @if ($errors->has('chain_id'))
                                    <span class="help-block">{{ $errors->first('chain_id') }}</span>
                                @endif                                         
                            </div>
                        </div> 

                        <div class="form-group{{ $errors->has('labeltype_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="labeltype_id">@lang('general.common.labeltype') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="js-example-basic-single form-control" id="labeltype_id" name="labeltype_id">
                                    @if(count($labelTypeData))                                        
                                        @foreach($labelTypeData as $rowLabelTypeData)
                                              <option value="{{$rowLabelTypeData->id}}">{{$rowLabelTypeData->name}} </option>
                                        @endforeach
                                    @endif
                                </select>  
                                 @if ($errors->has('labeltype_id'))
                                    <span class="help-block">{{ $errors->first('labeltype_id') }}</span>
                                @endif                                         
                            </div>
                        </div> 

                        <div class="form-group{{ $errors->has('department_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="department_id">@lang('general.common.department') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="js-example-basic-single form-control" id="department_id" name="department_id">
                                    @if(count($deptData))                                        
                                        @foreach($deptData as $rowDeptData)
                                              <option value="{{$rowDeptData->id}}">{{$rowDeptData->name}} </option>
                                        @endforeach
                                    @endif
                                </select>  
                                 @if ($errors->has('department_id'))
                                    <span class="help-block">{{ $errors->first('department_id') }}</span>
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
    </script>
@stop