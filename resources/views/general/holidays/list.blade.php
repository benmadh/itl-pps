@extends('templates.admin.layout')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-10 col-sm-10 col-xs-10">
                            <h2>@lang('holidays.formName')</h2>
                        </div> 
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <h2><a href="{{route('holidays.create')}}" class="btn btn-primary btn-xs" title="Create Record"><i class="fa fa-plus"></i> @lang('general.app.create_new') </a></h2>
                            <h2><a class="btn btn-info btn-xs" data-toggle="modal" data-target="#searchModal" title="Search Records"><i class="fa fa-search"></i> @lang('general.app.search') </a></h2>
                        </div>               
                    </div>                    
                    <div class="clearfix"></div>
                </div>                
                <div class="form-group">
                    <form method="post" action="{{ route('holidays.search') }}" data-parsley-validate class="form-horizontal form-label-left">
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
                                            <div class="form-group"> 
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="date">Date <span class="required">*</span>
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <div class="input-group date">
                                                        <input type="text" value="{{ $date }}" id="date" name="date" class="form-control col-md-4 col-xs-4" >
                                                        <span class="text-danger"><strong id="date-error"></strong></span>
                                                        <div class="input-group-addon">
                                                              <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                                      
                                        </div>
                                        <div class="row"> 
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
                                        </div>
                                        <div class="row"> 
                                            <div class="form-group{{ $errors->has('day_types_id') ? ' has-error' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="day_types_id">@lang('general.common.day_types_id') 
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <select class="form-control col-md-12 col-xs-12" id="day_types_id" name="day_types_id">
                                                        @if(count($dayTypes))
                                                        <option value=""></option>
                                                            @foreach($dayTypes as $row)
                                                                 <option value="{{$row->id}}" {{$row->id == Request::old('day_types_id') ?: '' ? 'selected="selected"' : ''}}>{{$row->name}}</option>
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
                                <th>@lang('general.common.company')</th> 
                                <th>@lang('general.common.day_types_id')</th>                               
                                <th>@lang('general.common.date')</th> 
                                <th>@lang('general.common.colorpicker_id')</th>                                
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
                                <th>@lang('general.common.day_types_id')</th>                               
                                <th>@lang('general.common.date')</th> 
                                <th>@lang('general.common.colorpicker_id')</th>                                
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
                                            <a href="{{ route('holidays.edit', ['id' => $row->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="Edit Record"></i> </a>
                                            <a href="{{ route('holidays.show', ['id' => $row->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="Delete Record"></i> </a>
                                        </td> 
                                        <td>{{$row->companies->name}}</td>    
                                        <td>{{$row->day_types->name}}</td>                             
                                        <td>{{$row->date}}</td> 
                                        <td style="background-color: {{$row->day_types->colorpicker_id}}"></td>        
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
