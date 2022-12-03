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
                            <h2>@lang('daytypes.edit')</h2>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2> <a href="{{route('daytypes.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('daytypes.update', ['id' => $dataObj->id]) }}" data-parsley-validate class="form-horizontal form-label-left">
                        
                        <div class="form-group{{ $errors->has('companies_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="companies_id">@lang('general.common.company') <span class="required">*</span>
                            </label>
                            <div class="col-md-8 col-sm-8 col-xs-8">
                                <select class="js-example-basic-single form-control" id="companies_id" name="companies_id">
                                    @if(count($dataCompanies))                                        
                                        @foreach($dataCompanies as $rowCompanies)
                                              <option value="{{$rowCompanies->id}}" {{ $rowCompanies->id == $dataObj->companies_id ? 'selected="selected"' : ''}}> {{$rowCompanies->name}}</option>
                                        @endforeach
                                    @endif
                                </select>  
                                 @if ($errors->has('companies_id'))
                                    <span class="help-block">{{ $errors->first('companies_id') }}</span>
                                @endif                                         
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">@lang('general.common.name') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{$dataObj->name}}" id="name" name="name" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group{{ $errors->has('colorpicker_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">@lang('general.common.colorpicker_id') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input style="height:40px;font-size:14pt;" type="text" value="{{$dataObj->colorpicker_id}}" id="colorpicker_id" name="colorpicker_id" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('colorpicker_id'))
                                    <span class="help-block">{{ $errors->first('colorpicker_id') }}</span>
                                @endif
                            </div>
                        </div>
                                                                                   
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                                <input name="_method" type="hidden" value="PUT">
                                <button type="submit" class="btn btn-success">@lang('general.form.save_changes')</button>
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
        $('#c0').minicolors({ inline:true});
        $('#c1').minicolors();
        $('#colorpicker_id').minicolors({ animationEasing: 'swing'});
        $('#c3').minicolors({format: 'rgb'});
    </script>
@stop