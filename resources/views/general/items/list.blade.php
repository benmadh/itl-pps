@extends('templates.admin.layout')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-10 col-sm-10 col-xs-10">
                            <h2>@lang('item.formName')</h2>
                        </div> 
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <h2><a href="{{route('items.create')}}" class="btn btn-primary btn-xs" title="Create Record"><i class="fa fa-plus"></i> @lang('general.app.create_new') </a></h2>
                            <h2><a class="btn btn-info btn-xs" data-toggle="modal" data-target="#searchModal" title="Search Records"><i class="fa fa-search"></i> @lang('general.app.search') </a></h2>
                        </div>               
                    </div>                    
                    <div class="clearfix"></div>
                </div>                
                <div class="form-group">
                    <form method="post" action="{{ route('items.search') }}" data-parsley-validate class="form-horizontal form-label-left">
                        {{ csrf_field() }}
                        <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="seachModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title" id="seachModalLabel">Search Details</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row"> 
                                            <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">             
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="code">@lang('general.common.code')</label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <input type="text" value="{{ Request::old('code') ?: '' }}" id="code" name="code" placeholder="Exp: RIB0002" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div> 

                                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">             
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="name">@lang('general.common.name')</label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <input type="text" value="{{ Request::old('name') ?: '' }}" id="name" name="name" placeholder="Exp: 3327 X 14 Blush Pink" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>                                                                
                                        </div>
                                        <div class="row"> 
                                            <div class="form-group{{ $errors->has('item_group_id') ? ' has-error' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="item_group_id">@lang('general.common.itemGroup') 
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <select class="form-control col-md-12 col-xs-12" id="item_group_id" name="item_group_id">
                                                        @if(count($itemGroupData))
                                                        <option value=""></option>
                                                            @foreach($itemGroupData as $rowDataItemGroups)
                                                                 <option value="{{$rowDataItemGroups->id}}" {{$rowDataItemGroups->id == Request::old('item_group_id') ?: '' ? 'selected="selected"' : ''}}>{{$rowDataItemGroups->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>                                           
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row"> 
                                            <div class="form-group{{ $errors->has('substrate_category_id') ? ' has-error' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="substrate_category_id">@lang('general.common.substrate_category') 
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <select class="form-control col-md-12 col-xs-12" id="substrate_category_id" name="substrate_category_id">
                                                        @if(count($substrateCatData))
                                                        <option value=""></option>
                                                            @foreach($substrateCatData as $rowDataSubstrateCat)
                                                                 <option value="{{$rowDataSubstrateCat->id}}" {{$rowDataSubstrateCat->id == Request::old('substrate_category_id') ?: '' ? 'selected="selected"' : ''}}>{{$rowDataSubstrateCat->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>                                           
                                                </div>
                                            </div>
                                        </div>

                                         <div class="row"> 
                                            <div class="form-group{{ $errors->has('unit_id') ? ' has-error' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="unit_id">@lang('general.common.unit') 
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <select class="form-control col-md-12 col-xs-12" id="unit_id" name="unit_id">
                                                        @if(count($unitsData))
                                                        <option value=""></option>
                                                            @foreach($unitsData as $rowDataUnits)
                                                                 <option value="{{$rowDataUnits->id}}" {{$rowDataUnits->id == Request::old('unit_id') ?: '' ? 'selected="selected"' : ''}}>{{$rowDataUnits->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>                                           
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </form>       
                </div>
                                
                <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>                                
                                <th>@lang('general.common.action')</th>      
                                <th>@lang('general.common.code')</th>                          
                                <th>@lang('general.common.name')</th>
                                <th>@lang('general.common.itemGroup')</th>
                                <th>@lang('general.common.substrate_category')</th>
                                <th>@lang('general.common.unit')</th>
                                <th>@lang('general.form.create_user')</th>
                                <th>@lang('general.form.create_at')</th>
                                <th>@lang('general.form.update_user')</th>
                                <th>@lang('general.form.update_at')</th>                                 
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>@lang('general.common.action')</th>
                                <th>@lang('general.common.code')</th>                                   
                                <th>@lang('general.common.name')</th>
                                <th>@lang('general.common.itemGroup')</th>
                                <th>@lang('general.common.substrate_category')</th>
                                <th>@lang('general.common.unit')</th>
                                <th>@lang('general.form.create_user')</th>
                                <th>@lang('general.form.create_at')</th>
                                <th>@lang('general.form.update_user')</th>
                                <th>@lang('general.form.update_at')</th>       
                            </tr>
                        </tfoot>
                        <tbody>
							@if (count($dataLst))								
                                @foreach($dataLst as $row)
                                    <tr>                               
                                        <td>                                   
                                            <a href="{{ route('items.edit', ['id' => $row->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="Edit Record"></i> </a>
                                            <a href="{{ route('items.show', ['id' => $row->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="Delete Record"></i> </a>
                                        </td>                               
                                        <td>{{$row->code}}</td>
                                        <td>{{$row->name}}</td>   
                                        <td>{{$row->itemGroups->name}}</td>
                                        <td>{{$row->substrateCategories->name}}</td>
                                        <td>{{$row->units->name}}</td>                                                                   
                                        <td>{{$row->userCreateInfo['name']}}</td>
                                        <td>{{$row->created_at}}</td>
                                        <td>{{$row->userUpdateInfo['name']}}</td>                                 
                                        <td>{{$row->updated_at}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
