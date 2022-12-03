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
                            <h2>@lang('customer.edit')</h2>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2> <a href="{{route('customers.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('customers.update', $dataObj->id) }}" data-parsley-validate class="form-horizontal form-label-left">
                        
                        
                        <div class="form-group{{ $errors->has('customer_group_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="customer_group_id">@lang('general.common.customerGroup') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="js-example-basic-single form-control" id="customer_group_id" name="customer_group_id">
                                    @if(count($dataCusGrp))                                        
                                        @foreach($dataCusGrp as $row)
                                              <option value="{{$row->id}}" {{ $row->id == $dataObj->customer_group_id ? 'selected="selected"' : ''}}> {{$row->name}}</option>
                                        @endforeach
                                    @endif
                                </select>  
                                 @if ($errors->has('customer_group_id'))
                                    <span class="help-block">{{ $errors->first('customer_group_id') }}</span>
                                @endif                                         
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('account_code') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account_code">@lang('general.common.account_code') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{$dataObj->account_code}}" id="account_code" name="account_code" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('account_code'))
                                    <span class="help-block">{{ $errors->first('account_code') }}</span>
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

                        <div class="form-group{{ $errors->has('adress_1') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adress_1">@lang('general.common.adress_1') 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{$dataObj->adress_1}}" id="adress_1" name="adress_1" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('adress_1'))
                                    <span class="help-block">{{ $errors->first('adress_1') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group{{ $errors->has('adress_2') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adress_2">@lang('general.common.adress_2') 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{$dataObj->adress_2}}" id="adress_2" name="adress_2" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('adress_2'))
                                    <span class="help-block">{{ $errors->first('adress_2') }}</span>
                                @endif
                            </div>
                        </div>   

                        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="city">@lang('general.common.city') 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{$dataObj->city}}" id="city" name="city" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('city'))
                                    <span class="help-block">{{ $errors->first('city') }}</span>
                                @endif
                            </div>
                        </div>                                              
                        
                        <div class="form-group{{ $errors->has('telephone_land') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="telephone_land">@lang('general.common.telephone_land') 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{$dataObj->telephone_land}}" id="telephone_land" name="telephone_land" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('telephone_land'))
                                    <span class="help-block">{{ $errors->first('telephone_land') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group{{ $errors->has('telephone_fax') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="telephone_fax">@lang('general.common.telephone_fax') 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{$dataObj->telephone_fax}}" id="telephone_fax" name="telephone_fax" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('telephone_fax'))
                                    <span class="help-block">{{ $errors->first('telephone_fax') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group{{ $errors->has('official_email') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="official_email">@lang('general.common.official_email') 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{$dataObj->official_email}}" id="official_email" name="official_email" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('official_email'))
                                    <span class="help-block">{{ $errors->first('official_email') }}</span>
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
        $(".js-example-basic-single").select2();
    });     
</script>
@stop