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
                            <h2>@lang('item.edit')</h2>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2> <a href="{{route('items.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('items.update', ['id' => $dataObj->id]) }}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                        
                        <div class="row"> 
                            <div class ="col-md-7">
                                <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">@lang('general.common.code') <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        <input type="text" value="{{$dataObj->code}}" id="code" name="code" class="form-control col-md-7 col-xs-12">
                                        @if ($errors->has('code'))
                                            <span class="help-block">{{ $errors->first('code') }}</span>
                                        @endif
                                    </div>
                                </div>    
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">@lang('general.common.name') <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        <input type="text" value="{{$dataObj->name}}" id="name" name="name" class="form-control col-md-7 col-xs-12">
                                        @if ($errors->has('name'))
                                            <span class="help-block">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div> 

                                <div class="form-group{{ $errors->has('item_group_id') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3" for="item_group_id">@lang('general.common.itemGroup') <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        <select class="js-example-basic-single form-control" id="item_group_id" name="item_group_id">
                                            @if(count($itemGroupData))                                        
                                                @foreach($itemGroupData as $rowDataItemGroup)
                                                      <option value="{{$rowDataItemGroup->id}}" {{ $rowDataItemGroup->id == $dataObj->item_group_id ? 'selected="selected"' : ''}}> {{$rowDataItemGroup->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>  
                                         @if ($errors->has('item_group_id'))
                                            <span class="help-block">{{ $errors->first('item_group_id') }}</span>
                                        @endif                                         
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('substrate_category_id') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3" for="substrate_category_id">@lang('general.common.substrate_category') <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        <select class="js-example-basic-single form-control" id="substrate_category_id" name="substrate_category_id">
                                            @if(count($substrateCatData))                                        
                                                @foreach($substrateCatData as $rowDataSubstrateCat)
                                                      <option value="{{$rowDataSubstrateCat->id}}" {{ $rowDataSubstrateCat->id == $dataObj->substrate_category_id ? 'selected="selected"' : ''}}> {{$rowDataSubstrateCat->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>  
                                         @if ($errors->has('substrate_category_id'))
                                            <span class="help-block">{{ $errors->first('substrate_category_id') }}</span>
                                        @endif                                         
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('unit_id') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3" for="unit_id">@lang('general.common.unit') <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        <select class="js-example-basic-single form-control" id="unit_id" name="unit_id">
                                            @if(count($unitsData))                                        
                                                @foreach($unitsData as $rowDataUnits)
                                                      <option value="{{$rowDataUnits->id}}" {{ $rowDataUnits->id == $dataObj->unit_id ? 'selected="selected"' : ''}}> {{$rowDataUnits->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>  
                                         @if ($errors->has('unit_id'))
                                            <span class="help-block">{{ $errors->first('unit_id') }}</span>
                                        @endif                                         
                                    </div>
                                </div>
                            </div>
                            <div class ="col-md-5">
                                <div class="form-group">                   
                                    <img src="{{ url('/') }}/upload/images/items/{{$dataObj->photo }}" width="350px" height="250px"/>
                                    <input type="file" id="picture" name="picture" >
                                    @if ($errors->has('picture'))
                                        <span class="help-block">{{ $errors->first('picture') }}</span>
                                    @endif
                                </div>
                                Max 2 mb
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12">
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
    </script>
@stop