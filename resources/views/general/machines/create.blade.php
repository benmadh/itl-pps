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
                            <h2>@lang('machines.create')</h2>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2> <a href="{{route('machines.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('machines.store') }}" data-parsley-validate class="form-horizontal form-label-left">
                        <div class="form-group{{ $errors->has('machin_number') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="machin_number">@lang('general.common.machin_number') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('machin_number') ?: '' }}" id="machin_number" name="machin_number" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('machin_number'))
                                    <span class="help-block">{{ $errors->first('machin_number') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group{{ $errors->has('asset_number') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="asset_number">@lang('general.common.asset_number') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('asset_number') ?: '' }}" id="asset_number" name="asset_number" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('asset_number'))
                                    <span class="help-block">{{ $errors->first('asset_number') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('no_of_colour_front') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_of_colour_front">@lang('general.common.no_of_colour_front') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('no_of_colour_front') ?: '' }}" id="no_of_colour_front" name="no_of_colour_front" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('no_of_colour_front'))
                                    <span class="help-block">{{ $errors->first('no_of_colour_front') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('no_of_colour_back') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_of_colour_back">@lang('general.common.no_of_colour_back') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('no_of_colour_back') ?: '' }}" id="no_of_colour_back" name="no_of_colour_back" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('no_of_colour_back'))
                                    <span class="help-block">{{ $errors->first('no_of_colour_back') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group{{ $errors->has('rpm') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="rpm">@lang('general.common.rpm') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('rpm') ?: '' }}" id="rpm" name="rpm" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('rpm'))
                                    <span class="help-block">{{ $errors->first('rpm') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group{{ $errors->has('machine_type_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="machine_type_id">@lang('general.common.machine_type') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="js-example-basic-single form-control" id="machine_type_id" name="machine_type_id">
                                    @if(count($dataMcTypes))                                        
                                        @foreach($dataMcTypes as $rowMcTypes)
                                            <option value="{{$rowMcTypes->id}}" {{$rowMcTypes->id == Request::old('machine_type_id') ? 'selected="selected"' : ''}}>{{$rowMcTypes->name}}</option>
                                        @endforeach
                                    @endif
                                </select>  
                                @if ($errors->has('machine_type_id'))
                                    <span class="help-block">{{ $errors->first('machine_type_id') }}</span>
                                @endif                                         
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('machine_category_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="machine_category_id">@lang('general.common.machine_category') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="js-example-basic-single form-control" id="machine_category_id" name="machine_category_id">
                                    @if(count($dataMachineCat))                                        
                                        @foreach($dataMachineCat as $rowDataMcCat)
                                            <option value="{{$rowDataMcCat->id}}" {{$rowDataMcCat->id == Request::old('machine_category_id') ? 'selected="selected"' : ''}}>{{$rowDataMcCat->name}}</option>
                                        @endforeach
                                    @endif
                                </select>  
                                 @if ($errors->has('machine_category_id'))
                                    <span class="help-block">{{ $errors->first('machine_category_id') }}</span>
                                @endif                                         
                            </div>
                        </div>                        

                        <div class="form-group{{ $errors->has('wheel_type_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="wheel_type_id">@lang('general.common.wheel_type') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="js-example-basic-single form-control" id="wheel_type_id" name="wheel_type_id">
                                    @if(count($dataWheelTypes))                                        
                                        @foreach($dataWheelTypes as $rowWheelTypes)
                                            <option value="{{$rowWheelTypes->id}}" {{$rowWheelTypes->id == Request::old('wheel_type_id') ? 'selected="selected"' : ''}}>{{$rowWheelTypes->name}}</option>
                                        @endforeach
                                    @endif
                                </select>  
                                 @if ($errors->has('wheel_type_id'))
                                    <span class="help-block">{{ $errors->first('wheel_type_id') }}</span>
                                @endif                                         
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cylinders_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="cylinders_id">@lang('general.common.cylinders') <span class="required">*</span> 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="js-example-basic-multiple form-control" id="cylinders_id" name="cylinders_id[]" multiple>
                                    @if(count($cylinderData))
                                        @foreach($cylinderData as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('cylinders_id'))
                                <span class="help-block">{{ $errors->first('cylinders_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('condition_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="condition_id">@lang('general.common.condition') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="js-example-basic-single form-control" id="condition_id" name="condition_id">
                                    @if(count($dataConditions))                                        
                                        @foreach($dataConditions as $rowConditions)
                                            <option value="{{$rowConditions->id}}" {{$rowConditions->id == Request::old('condition_id') ? 'selected="selected"' : ''}}>{{$rowConditions->name}}</option>
                                        @endforeach
                                    @endif
                                </select>  
                                 @if ($errors->has('condition_id'))
                                    <span class="help-block">{{ $errors->first('condition_id') }}</span>
                                @endif                                         
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="company_id">@lang('general.common.company') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="js-example-basic-single form-control" id="company_id" name="company_id" onchange="getLocationList()">
                                    @if(count($dataCompanies)) 
                                    <option value=""></option>                                       
                                        @foreach($dataCompanies as $rowCompanies)
                                            <option value="{{$rowCompanies->id}}">{{$rowCompanies->name}} </option>
                                        @endforeach
                                    @endif
                                </select>  
                                 @if ($errors->has('company_id'))
                                    <span class="help-block">{{ $errors->first('company_id') }}</span>
                                @endif                                         
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="location_id">@lang('general.common.location') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="form-control" id="location_id" name="location_id">
                                    <option value=""></option>                                              
                                </select> 
                                @if ($errors->has('location_id'))
                                    <span class="help-block">{{ $errors->first('location_id') }}</span>
                                @endif                                          
                            </div>
                        </div>                        

                        <div class="form-group{{ $errors->has('department_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="department_id">@lang('general.common.department') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="js-example-basic-single form-control" id="department_id" name="department_id">
                                    @if(count($dataDepts))                                        
                                        @foreach($dataDepts as $rowDepts)                                              
                                            <option value="{{$rowDepts->id}}" {{$rowDepts->id == Request::old('department_id') ? 'selected="selected"' : ''}}>{{$rowDepts->name}}</option>
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

        function getLocationList() {
            var company_id = document.getElementById("company_id").value;           
            $.get('{{ url('getLocationList') }}/'+company_id, function(data){ 
                $('#location_id').empty();
                $('#location_id').append('<option value=""></option>');            
                $.each(data,function(index, dataObj){                    
                   $('#location_id').append('<option value="'+dataObj.id+'">'+dataObj.name+'</option>');
                });                
            });
        }

    </script>
@stop