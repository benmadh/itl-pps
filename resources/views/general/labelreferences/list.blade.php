@extends('templates.admin.layout')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-10 col-sm-10 col-xs-10">
                            <h2>@lang('labelreferences.formName')</h2>
                        </div> 
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <h2><a href="{{route('labelreferences.create')}}" class="btn btn-primary btn-xs" title="Create Record"><i class="fa fa-plus"></i> @lang('general.app.create_new') </a></h2>
                            <h2><a class="btn btn-info btn-xs" data-toggle="modal" data-target="#searchModal" title="Search Records"><i class="fa fa-search"></i> @lang('general.app.search') </a></h2>
                        </div>               
                    </div>                    
                    <div class="clearfix"></div>
                </div>                
                <div class="form-group">
                    <form method="post" action="{{ route('labelreferences.search') }}" data-parsley-validate class="form-horizontal form-label-left">
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
                                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">             
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="name">@lang('general.common.name')</label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <input type="text" value="{{ Request::old('name') ?: '' }}" id="name" name="name" placeholder="Exp: LB 2262 C/109" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">             
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="description">@lang('general.common.description')</label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <input type="text" value="{{ Request::old('description') ?: '' }}" id="description" name="description" placeholder="" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div> 

                                        </div>
                                        <div class="row"> 
                                            <div class="form-group{{ $errors->has('chain_id') ? ' has-error' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="chain_id">@lang('general.common.chain') 
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <select class="form-control col-md-12 col-xs-12" id="chain_id" name="chain_id">
                                                        @if(count($chainsData))
                                                        <option value=""></option>
                                                            @foreach($chainsData as $rowChainsData)
                                                                 <option value="{{$rowChainsData->id}}" {{$rowChainsData->id == Request::old('chain_id') ?: '' ? 'selected="selected"' : ''}}>{{$rowChainsData->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>                                           
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row"> 
                                            <div class="form-group{{ $errors->has('labeltype_id') ? ' has-error' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="labeltype_id">@lang('general.common.labeltype') 
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <select class="form-control col-md-12 col-xs-12" id="labeltype_id" name="labeltype_id">
                                                        @if(count($labelTypeData))
                                                        <option value=""></option>
                                                            @foreach($labelTypeData as $rowLabelTypeData)
                                                                 <option value="{{$rowLabelTypeData->id}}" {{$rowLabelTypeData->id == Request::old('labeltype_id') ?: '' ? 'selected="selected"' : ''}}>{{$rowLabelTypeData->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>                                           
                                                </div>
                                            </div>
                                        </div>

                                         <div class="row"> 
                                            <div class="form-group{{ $errors->has('department_id') ? ' has-error' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="department_id">@lang('general.common.department') 
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <select class="form-control col-md-12 col-xs-12" id="department_id" name="department_id">
                                                        @if(count($deptData))
                                                        <option value=""></option>
                                                            @foreach($deptData as $rowDeptData)
                                                                 <option value="{{$rowDeptData->id}}" {{$rowDeptData->id == Request::old('department_id') ?: '' ? 'selected="selected"' : ''}}>{{$rowDeptData->name}}</option>
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
                                <th>@lang('general.common.updates')</th>     
                                <th>@lang('general.common.name')</th>                          
                                <th>@lang('general.common.description')</th>
                                <th>@lang('general.common.ground_colour')</th>
                                <th>@lang('general.common.print_colour')</th>
                                <th>@lang('general.common.combo')</th>
                                <th>@lang('general.common.default_lenght')</th>
                                <th>@lang('general.common.default_width')</th>
                                <th>@lang('general.common.chain')</th>
                                <th>@lang('general.common.labeltype')</th>
                                <th>@lang('general.common.department')</th>
                                <th>@lang('general.form.create_user')</th>
                                <th>@lang('general.form.create_at')</th>
                                <th>@lang('general.form.update_user')</th>
                                <th>@lang('general.form.update_at')</th>                                 
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>@lang('general.common.action')</th> 
                                <th>@lang('general.common.updates')</th>      
                                <th>@lang('general.common.name')</th>                          
                                <th>@lang('general.common.description')</th>
                                <th>@lang('general.common.ground_colour')</th>
                                <th>@lang('general.common.print_colour')</th>
                                <th>@lang('general.common.combo')</th>
                                <th>@lang('general.common.default_lenght')</th>
                                <th>@lang('general.common.default_width')</th>
                                <th>@lang('general.common.chain')</th>
                                <th>@lang('general.common.labeltype')</th>
                                <th>@lang('general.common.department')</th>
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
                                            <a href="{{ route('labelreferences.edit', ['id' => $row->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="Edit Record"></i> </a>
                                            <a href="{{ route('labelreferences.show', ['id' => $row->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="Delete Record"></i> </a>
                                        </td> 
                                        <td>
                                            <a href="{{ route('labelreferences.edit_item_references', ['id' => $row->id]) }}" class="btn btn-success btn-xs {{ Auth::user()->can('access_item_bom') ? 'active' : 'disabled' }}"><i class="fa fa-pencil" title="Update Item BOM"></i> </a>
                                        </td>
                                        @if ($row->file_name)
                                            <td> <a href="{{ url('/') }}/upload/images/labelreferences/{{$row->file}}">{{$row->name}}</a></td>
                                        @else
                                            <td>{{$row->name}}</td>
                                        @endif 
                                        <td>{{$row->description}}</td> 
                                        <td>{{$row->ground_colour}}</td> 
                                        <td>{{$row->print_colour}}</td> 
                                        <td>{{$row->combo}}</td> 
                                        <td>{{$row->default_lenght}}</td> 
                                        <td>{{$row->default_width}}</td>
                                        <td>{{$row->chains->name}}</td>
                                        <td>{{$row->labelTypes->name}}</td>
                                        <td>{{$row->departments->name}}</td>                                                                   
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
