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
                            <h2>@lang('cylinders.create')</h2>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2> <a href="{{route('cylinders.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('cylinders.store') }}" data-parsley-validate class="form-horizontal form-label-left">
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
                        <div class="form-group{{ $errors->has('wheel_no') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="wheel_no">@lang('general.common.wheel_no') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('wheel_no') ?: '' }}" id="wheel_no" name="wheel_no" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('wheel_no'))
                                    <span class="help-block">{{ $errors->first('wheel_no') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('lengths_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="lengths_id">@lang('general.common.label_lenght') <span class="required">*</span> 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="js-example-basic-multiple form-control" id="lengths_id" name="lengths_id[]" multiple>
                                    @if(count($lengths))
                                        @foreach($lengths as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('lengths_id'))
                                <span class="help-block">{{ $errors->first('lengths_id') }}</span>
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