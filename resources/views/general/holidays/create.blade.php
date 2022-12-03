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
                            <h2>@lang('holidays.create')</h2>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2> <a href="{{route('holidays.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('holidays.store') }}" data-parsley-validate class="form-horizontal form-label-left">

                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="date">Date <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="input-group date">
                                    <input type="text" value="{{ $date }}" id="date" name="date" class="form-control col-md-4 col-xs-4" >
                                    <span class="text-danger"><strong id="date-error"></strong></span>
                                    <div class="input-group-addon">
                                          <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>  

                        <div class="form-group{{ $errors->has('companies_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="companies_id">@lang('general.common.company') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="js-example-basic-single form-control" id="companies_id" name="companies_id" onchange="getDayTypeList()">
                                    @if(count($dataCompanies)) 
                                    <option value=""></option>                                       
                                        @foreach($dataCompanies as $rowCompanies)
                                            <option value="{{$rowCompanies->id}}">{{$rowCompanies->name}} </option>
                                        @endforeach
                                    @endif
                                </select>  
                                 @if ($errors->has('companies_id'))
                                    <span class="help-block">{{ $errors->first('companies_id') }}</span>
                                @endif                                         
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('day_types_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="day_types_id">@lang('general.common.day_types_id') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <select class="form-control" id="day_types_id" name="day_types_id">
                                    <option value=""></option>                                              
                                </select> 
                                @if ($errors->has('day_types_id'))
                                    <span class="help-block">{{ $errors->first('day_types_id') }}</span>
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

        function getDayTypeList() {
            var companies_id = document.getElementById("companies_id").value;           
            $.get('{{ url('getDayTypeList') }}/'+companies_id, function(data){ 
                $('#day_types_id').empty();                           
                $.each(data,function(index, dataObj){                    
                   $('#day_types_id').append('<option value="'+dataObj.id+'">'+dataObj.name+'</option>');
                });                
            });
        }      
    </script>
@stop