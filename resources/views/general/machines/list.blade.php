@extends('templates.admin.layout')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-10 col-sm-10 col-xs-10">
                            <h2>@lang('machines.formName')</h2>
                        </div> 
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <h2><a href="{{route('machines.create')}}" class="btn btn-primary btn-xs" title="Create Record"><i class="fa fa-plus"></i> @lang('general.app.create_new') </a></h2>
                            <h2><a class="btn btn-info btn-xs" data-toggle="modal" data-target="#searchModal" title="Search Records"><i class="fa fa-search"></i> @lang('general.app.search') </a></h2>
                        </div>               
                    </div>                    
                    <div class="clearfix"></div>
                </div>                
                <div class="form-group">
                    <form method="post" action="{{ route('machines.search') }}" data-parsley-validate class="form-horizontal form-label-left">
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
                                            <div class="form-group{{ $errors->has('machin_number') ? ' has-error' : '' }}">             
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="machin_number">@lang('general.common.machin_number')</label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <input type="text" value="{{ Request::old('machin_number') ?: '' }}" id="machin_number" name="machin_number" placeholder="Exp: H&Y-21" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>                                                                
                                        </div>
                                        <div class="row">                            
                                            <div class="form-group{{ $errors->has('asset_number') ? ' has-error' : '' }}">             
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="asset_number">@lang('general.common.asset_number')</label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <input type="text" value="{{ Request::old('asset_number') ?: '' }}" id="asset_number" name="asset_number" placeholder="Exp: FL/MC/021" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>                                                                
                                        </div>

                                        <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="company_id">@lang('general.common.company') 
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <select class="form-control col-md-12 col-xs-12" id="company_id" name="company_id">
                                                    @if(count($dataCompanies))
                                                    <option value=""></option>
                                                        @foreach($dataCompanies as $rowDataCompanies)
                                                             <option value="{{$rowDataCompanies->id}}" {{$rowDataCompanies->id == Request::old('company_id') ?: '' ? 'selected="selected"' : ''}}>{{$rowDataCompanies->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>                                           
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="location_id">@lang('general.common.location') 
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <select class="form-control col-md-12 col-xs-12" id="location_id" name="location_id">
                                                    @if(count($dataLocations))
                                                    <option value=""></option>
                                                        @foreach($dataLocations as $rowDataLocations)
                                                             <option value="{{$rowDataLocations->id}}" {{$rowDataLocations->id == Request::old('location_id') ?: '' ? 'selected="selected"' : ''}}>{{$rowDataLocations->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>                                           
                                            </div>
                                        </div>


                                        <div class="form-group{{ $errors->has('department_id') ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="department_id">@lang('general.common.department') 
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <select class="form-control col-md-12 col-xs-12" id="department_id" name="department_id">
                                                    @if(count($dataDepts))
                                                    <option value=""></option>
                                                        @foreach($dataDepts as $row)
                                                             <option value="{{$row->id}}" {{$row->id == Request::old('department_id') ?: '' ? 'selected="selected"' : ''}}>{{$row->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>                                           
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('machine_type_id') ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="machine_type_id">@lang('general.common.machine_type') 
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <select class="form-control col-md-12 col-xs-12" id="machine_type_id" name="machine_type_id">
                                                    @if(count($dataMcTypes))
                                                    <option value=""></option>
                                                        @foreach($dataMcTypes as $rowDataMcTypes)
                                                             <option value="{{$rowDataMcTypes->id}}" {{$rowDataMcTypes->id == Request::old('machine_type_id') ?: '' ? 'selected="selected"' : ''}}>{{$rowDataMcTypes->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>                                           
                                            </div>
                                        </div>
                                        
                                        <div class="form-group{{ $errors->has('machine_category_id') ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="machine_category_id">@lang('general.common.machine_category') 
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <select class="form-control col-md-12 col-xs-12" id="machine_category_id" name="machine_category_id">
                                                    @if(count($dataMachineCat))
                                                    <option value=""></option>
                                                        @foreach($dataMachineCat as $rowDataMcCat)
                                                             <option value="{{$rowDataMcCat->id}}" {{$rowDataMcCat->id == Request::old('machine_category_id') ?: '' ? 'selected="selected"' : ''}}>{{$rowDataMcCat->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>                                           
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('condition_id') ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="condition_id">@lang('general.common.condition') 
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <select class="form-control col-md-12 col-xs-12" id="condition_id" name="condition_id">
                                                    @if(count($dataConditions))
                                                    <option value=""></option>
                                                        @foreach($dataConditions as $rowDataConditions)
                                                             <option value="{{$rowDataConditions->id}}" {{$rowDataConditions->id == Request::old('condition_id') ?: '' ? 'selected="selected"' : ''}}>{{$rowDataConditions->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>                                           
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
                                <th>@lang('general.common.company')</th>
                                <th>@lang('general.common.location')</th>
                                <th>@lang('general.common.department')</th>
                                <th>@lang('general.common.machin_number')</th> 
                                <th>@lang('general.common.asset_number')</th> 
                                <th>@lang('general.common.no_of_colour_front')</th> 
                                <th>@lang('general.common.no_of_colour_back')</th> 
                                <th>@lang('general.common.rpm')</th> 
                                <th>@lang('general.common.machine_type')</th> 
                                <th>@lang('general.common.machine_category')</th>                                
                                <th>@lang('general.common.condition')</th>
                                <th>@lang('general.common.isActive')</th>
                                <th>@lang('general.common.wheel_type')</th>
                                <th>@lang('general.common.cylinders')</th>
                                <th>@lang('general.form.create_user')</th>
                                <th>@lang('general.form.create_at')</th>
                                <th>@lang('general.form.update_user')</th>
                                <th>@lang('general.form.update_at')</th>                                 
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>@lang('general.common.action')</th>                                
                                <th>@lang('general.common.company')</th>
                                <th>@lang('general.common.location')</th>
                                <th>@lang('general.common.department')</th>
                                <th>@lang('general.common.machin_number')</th> 
                                <th>@lang('general.common.asset_number')</th> 
                                <th>@lang('general.common.no_of_colour_front')</th> 
                                <th>@lang('general.common.no_of_colour_back')</th> 
                                <th>@lang('general.common.rpm')</th> 
                                <th>@lang('general.common.machine_type')</th> 
                                <th>@lang('general.common.machine_category')</th>                                
                                <th>@lang('general.common.condition')</th>
                                <th>@lang('general.common.isActive')</th>
                                <th>@lang('general.common.wheel_type')</th>
                                <th>@lang('general.common.cylinders')</th>
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
                                            <a href="{{ route('machines.edit', ['id' => $row->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="Edit Record"></i> </a>
                                            <a href="{{ route('machines.show', ['id' => $row->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="Delete Record"></i> </a>
                                        </td>                               
                                        <td>{{$row->companies->name}}</td> 
                                        <td>{{$row->locations->name}}</td> 
                                        <td>{{$row->departments->name}}</td>
                                        <td>{{$row->machin_number}}</td>
                                        <td>{{$row->asset_number}}</td>
                                        <td>{{$row->no_of_colour_front}}</td>
                                        <td>{{$row->no_of_colour_back}}</td>
                                        <td>{{$row->rpm}}</td>
                                        <td>{{$row->machine_types->name}}</td>
                                        <td>{{$row->machine_categories->name}}</td>
                                        <td>{{$row->conditions->name}}</td>
                                        <td>
                                            @if ($row->isActive == '1')                                
                                                <h2><span class="label label-success">Active</span> </h2> 
                                            @endif

                                            @if ($row->isActive == '0')                                
                                                <h2><span class="label label-danger">Non Active</span> </h2> 
                                            @endif
                                        </td>
                                        <td>{{$row->wheel_types->name}}</td>
                                        <td>
                                            @foreach($row->cylinders as $rowData)                  
                                                <span class="label label-info label-many">{{ $rowData->name }}</span> 
                                            @endforeach
                                        </td>                                                                     
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
@section ('js')
<script>  
    $(document).ready(function() {           
        $(".js-example-basic-single").select2();
    });     
</script>
@stop