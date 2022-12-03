@extends('templates.admin.layout')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-10 col-sm-10 col-xs-10">
                            <h2>@lang('holdMachines.formName')</h2>
                        </div> 
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <h2><a href="{{route('holdmachines.create')}}" class="btn btn-primary btn-xs" title="Create Record"><i class="fa fa-plus"></i> @lang('general.app.create_new') </a></h2>
                            <h2><a class="btn btn-info btn-xs" data-toggle="modal" data-target="#searchModal" title="Search Records"><i class="fa fa-search"></i> @lang('general.app.search') </a></h2>
                        </div>               
                    </div>                    
                    <div class="clearfix"></div>
                </div>                
                <div class="form-group">
                    <form method="post" action="{{ route('holdmachines.search') }}" data-parsley-validate class="form-horizontal form-label-left">
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
                                            <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}"> 
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="date">Date</label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <div class="input-group date margin-bottom: 0px;">
                                                        <input type="text" value="{{ $date }}" id="date" name="date" class="form-control col-md-8 col-xs-8">
                                                        <div class="input-group-addon">
                                                          <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                                                
                                        </div>

                                        <div class="row">  
                                            <div class="form-group{{ $errors->has('department_id') ? ' has-error' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="department_id">@lang('general.common.department') 
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <select class="form-control col-md-12 col-xs-12" id="department_id" name="department_id" onchange="getMachineList()">
                                                        @if(count($dataDepts))
                                                        <option value=""></option>
                                                            @foreach($dataDepts as $row)
                                                                 <option value="{{$row->id}}" {{$row->id == Request::old('department_id') ?: '' ? 'selected="selected"' : ''}}>{{$row->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>                                           
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group{{ $errors->has('machine_id') ? ' has-error' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="machine_id">@lang('general.common.machine') 
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <select class="form-control" id="machine_id" name="machine_id">
                                                        <option value=""></option>                                              
                                                    </select>                                                                              
                                                </div>
                                            </div> 
                                        </div> 

                                        <div class="row">  
                                            <div class="form-group{{ $errors->has('shift') ? ' has-error' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="shift">@lang('general.common.shift') 
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <select class="form-control col-md-12 col-xs-12" id="shift" name="shift">
                                                        @if(count($shiftList))
                                                        <option value=""></option>
                                                            @foreach($shiftList as $row)
                                                                 <option value="{{$row}}" {{$row == Request::old('shift') ?: '' ? 'selected="selected"' : ''}}>{{$row}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>                                           
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">  
                                            <div class="form-group{{ $errors->has('mc_hold_reason_id') ? ' has-error' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="mc_hold_reason_id">@lang('general.common.reason') 
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-8">
                                                    <select class="form-control col-md-12 col-xs-12" id="mc_hold_reason_id" name="mc_hold_reason_id">
                                                        @if(count($dataReason))
                                                        <option value=""></option>
                                                            @foreach($dataReason as $row)
                                                                 <option value="{{$row->id}}" {{$row->id == Request::old('mc_hold_reason_id') ?: '' ? 'selected="selected"' : ''}}>{{$row->name}}</option>
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
                                <th>@lang('general.common.department')</th>   
                                <th>@lang('general.common.machine')</th>                             
                                <th>@lang('general.common.date')</th>
                                <th>@lang('general.common.shift')</th>
                                <th>@lang('general.common.reason')</th>
                                <th>@lang('general.form.create_user')</th>
                                <th>@lang('general.form.create_at')</th>
                                <th>@lang('general.form.update_user')</th>
                                <th>@lang('general.form.update_at')</th>                                 
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>@lang('general.common.action')</th>
                                <th>@lang('general.common.department')</th>   
                                <th>@lang('general.common.machine')</th>                             
                                <th>@lang('general.common.date')</th>
                                <th>@lang('general.common.shift')</th>
                                <th>@lang('general.common.reason')</th>
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
                                            <a href="{{ route('holdmachines.edit', $row->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil" title="Edit Record"></i> </a>
                                            <a href="{{ route('holdmachines.show', $row->id) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o" title="Delete Record"></i> </a>
                                        </td>                               
                                        <td>{{$row->departments->name}}</td>  
                                        <td>{{$row->machines->machin_number}}</td>
                                        <td>{{$row->date}}</td> 
                                        <td>{{$row->shift}}</td> 
                                        <td>{{$row->mc_hold_reasons->name}}</td>                                                                    
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
            $(".js-example-basic-multiple").select2();
            $(".js-example-basic-single").select2();
        });

        function getMachineList() {
            var department_id = document.getElementById("department_id").value;           
            $.get('{{ url('getMachineList') }}/'+department_id, function(data){
                $('#machine_id').empty();
                $('#machine_id').append('<option value=""></option>');            
                $.each(data,function(index, dataObj){                    
                   $('#machine_id').append('<option value="'+dataObj.id+'">'+dataObj.machin_number+'</option>');
                });                
            });
        }

    </script>
@stop
