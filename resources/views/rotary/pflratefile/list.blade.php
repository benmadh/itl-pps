@extends('templates.admin.layout')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-10 col-sm-10 col-xs-10">
                            <h2>@lang('pflratefile.formName')</h2>
                        </div> 
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <h2><a href="{{route('pflratefile.create')}}" class="btn btn-primary btn-xs" title="Create Record"><i class="fa fa-plus"></i> @lang('general.app.create_new') </a></h2>
                            <h2><a class="btn btn-info btn-xs" data-toggle="modal" data-target="#searchModal" title="Search Records"><i class="fa fa-search"></i> @lang('general.app.search') </a></h2>
                        </div>               
                    </div>                    
                    <div class="clearfix"></div>
                </div>                
                <div class="form-group">
                    <form method="post" action="{{ route('pflratefile.search') }}" data-parsley-validate class="form-horizontal form-label-left">
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
                                        <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="company_id">@lang('general.common.company') 
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <select class="form-control col-md-12 col-xs-12" id="company_id" name="company_id">
                                                    @if(count($comData))
                                                    <option value=""></option>
                                                        @foreach($comData as $row)
                                                             <option value="{{$row->id}}" {{$row->id == Request::old('company_id') ?: '' ? 'selected="selected"' : ''}}>{{$row->name}}</option>
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
                                                    @if(count($locData))
                                                    <option value=""></option>
                                                        @foreach($locData as $row)
                                                             <option value="{{$row->id}}" {{$row->id == Request::old('location_id') ?: '' ? 'selected="selected"' : ''}}>{{$row->name}}</option>
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
                                                    @if(count($mctData))
                                                    <option value=""></option>
                                                        @foreach($mctData as $row)
                                                             <option value="{{$row->id}}" {{$row->id == Request::old('machine_type_id') ?: '' ? 'selected="selected"' : ''}}>{{$row->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>                                           
                                            </div>
                                        </div>
                                        
                                        <div class="form-group{{ $errors->has('table_type') ? ' has-error' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-3" for="table_type">@lang('general.common.table_type') 
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-8">
                                                <select class="form-control col-md-12 col-xs-12" id="table_type" name="table_type">
                                                    @if(count($tableTypeLst))
                                                    <option value=""></option>
                                                        @foreach($tableTypeLst as $row)
                                                            <option value="{{$row}}" {{$row == Request::old('table_type') ?: '' ? 'selected="selected"' : ''}}>{{$row}}</option>
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
                                <th>@lang('general.common.machine_type')</th>
                                <th>@lang('general.common.calculation_metrics')</th>
                                <th>@lang('general.common.isActive')</th>
                                <th>@lang('pflratefile.each_clr_front_waste_mtr')</th> 
                                <th>@lang('pflratefile.each_clr_front_setup_time_min')</th>
                                <th>@lang('pflratefile.each_clr_back_waste_mtr')</th>
                                <th>@lang('pflratefile.each_clr_back_setup_time_min')</th>
                                <th>@lang('pflratefile.plate_change_waste_mtr')</th>
                                <th>@lang('pflratefile.plate_change_setup_time_min')</th>
                                <th>@lang('pflratefile.tape_setup_waste_mtr')</th>
                                <th>@lang('pflratefile.tape_setup_setup_time_min')</th>
                                <th>@lang('pflratefile.reference_change_time_min')</th>                             
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
                                <th>@lang('general.common.machine_type')</th>
                                <th>@lang('general.common.calculation_metrics')</th>
                                <th>@lang('general.common.isActive')</th>
                                <th>@lang('pflratefile.each_clr_front_waste_mtr')</th> 
                                <th>@lang('pflratefile.each_clr_front_setup_time_min')</th>
                                <th>@lang('pflratefile.each_clr_back_waste_mtr')</th>
                                <th>@lang('pflratefile.each_clr_back_setup_time_min')</th>
                                <th>@lang('pflratefile.plate_change_waste_mtr')</th>
                                <th>@lang('pflratefile.plate_change_setup_time_min')</th>
                                <th>@lang('pflratefile.tape_setup_waste_mtr')</th>
                                <th>@lang('pflratefile.tape_setup_setup_time_min')</th>
                                <th>@lang('pflratefile.reference_change_time_min')</th>                             
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
                                            <a href="{{ route('pflratefile.edit', ['id' => $row->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="Edit Record"></i> </a>
                                            <a href="{{ route('pflratefile.edit_machine_speed', ['id' => $row->id]) }}" class="btn btn-success btn-xs"><i class="fa fa-pencil" title="Update Machine Speed"></i> </a>
                                            <a href="{{ route('pflratefile.show', ['id' => $row->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="Delete Record"></i> </a>
                                        </td>                               
                                        <td>{{$row->companies->name}}</td> 
                                        <td>{{$row->locations->name}}</td>
                                        <td>{{$row->departments->name}}</td> 
                                        <td>{{$row->machine_types->name}}</td>
                                        <td>{{$row->table_type}}</td>
                                        <td>
                                            @if ($row->isActive == '1')                                
                                                <h2><span class="label label-success">Active</span> </h2> 
                                            @endif

                                            @if ($row->isActive == '0')                                
                                                <h2><span class="label label-danger">Non Active</span> </h2> 
                                            @endif
                                        </td>                                        
                                        <td>{{$row->each_clr_front_waste_mtr}}</td>
                                        <td>{{$row->each_clr_front_setup_time_min}}</td>
                                        <td>{{$row->each_clr_back_waste_mtr}}</td>
                                        <td>{{$row->each_clr_back_setup_time_min}}</td>
                                        <td>{{$row->plate_change_waste_mtr}}</td>
                                        <td>{{$row->plate_change_setup_time_min}}</td>
                                        <td>{{$row->tape_setup_waste_mtr}}</td>
                                        <td>{{$row->tape_setup_setup_time_min}}</td>
                                        <td>{{$row->reference_change_time_min}}</td>                                                                                                         
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