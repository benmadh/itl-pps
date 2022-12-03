@extends('templates.admin.layout')

@section('content')
<div class="">

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-10 col-sm-10 col-xs-10">
                            <h2>@lang('role.roles')</h2>
                        </div> 
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <h2><a href="{{route('roles.create')}}" class="btn btn-primary btn-xs" title="Create Record"><i class="fa fa-plus"></i> @lang('general.app.create_new') </a></h2>
                            <h2><a class="btn btn-info btn-xs" data-toggle="modal" data-target="#searchModal" title="Search Records"><i class="fa fa-search"></i> @lang('general.app.search') </a></h2>
                        </div>               
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <h2 class="box-title">@lang('general.app.search')</h2>         
                    <form method="post" action="{{ route('roles.search') }}" data-parsley-validate class="form-horizontal form-label-left">
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
                                                    <input type="text" value="{{ Request::old('name') ?: '' }}" id="name" name="name" placeholder="Exp: admin" class="form-control col-md-7 col-xs-12">
                                                </div>
                                            </div>                             
                                        </div>
                                       
                                        <div class="row">                            
                                            <div class="form-group{{ $errors->has('display_name') ? ' has-error' : '' }}">             
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="display_name">@lang('permission.display_name')</label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <input type="text" value="{{ Request::old('display_name') ?: '' }}" id="display_name" name="display_name" placeholder="Exp: Administrator" class="form-control col-md-7 col-xs-12">
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
                                <th>@lang('role.action')</th>                               
                                <th>@lang('role.name')</th>
                                <th>@lang('role.display_name')</th>
                                <th>@lang('role.description')</th> 
                                <th>@lang('general.form.create_user')</th>
                                <th>@lang('general.form.create_at')</th>
                                <th>@lang('general.form.update_user')</th>
                                <th>@lang('general.form.update_at')</th>                                 
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>                               
                                <th>@lang('role.action')</th>                               
                                <th>@lang('role.name')</th>
                                <th>@lang('role.display_name')</th>
                                <th>@lang('role.description')</th> 
                                <th>@lang('general.form.create_user')</th>
                                <th>@lang('general.form.create_at')</th>
                                <th>@lang('general.form.update_user')</th>
                                <th>@lang('general.form.update_at')</th>  
                            </tr>
                        </tfoot>
                        <tbody>
							@if (count($roles))								
                                @foreach($roles as $row)
                                    <tr>                              
                                        <td>                                    
                                            <a href="{{ route('roles.edit', ['id' => $row->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="Edit"></i> </a>                                   
                                            <a href="{{ route('roles.show', ['id' => $row->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="Delete"></i> </a>
                                        </td>                              
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->display_name}}</td>
                                        <td>{{$row->description}}</td>
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
