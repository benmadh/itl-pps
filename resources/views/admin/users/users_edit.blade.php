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
                            <h2>@lang('users.edit_user')</h2>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2> <a href="{{route('users.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('users.update', ['id' => $user->id]) }}" data-parsley-validate class="form-horizontal form-label-left">
                        
                        <div class="form-group{{ $errors->has('locations_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="locations_id">@lang('general.common.location') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="js-example-basic-single form-control" id="locations_id" name="locations_id">
                                    @if(count($locations))                                        
                                        @foreach($locations as $row)
                                              <option value="{{$row->id}}" {{ $row->id == $user->locations_id ? 'selected="selected"' : ''}}> {{$row->name}}</option>
                                        @endforeach
                                    @endif
                                </select>  
                                 @if ($errors->has('locations_id'))
                                    <span class="help-block">{{ $errors->first('locations_id') }}</span>
                                @endif                                         
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">@lang('users.user_name') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{$user->user_name}}" id="user_name" name="user_name" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('user_name'))
                                <span class="help-block">{{ $errors->first('user_name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">@lang('users.name') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{$user->name}}" id="name" name="name" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">@lang('users.email') <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{$user->email}}" id="email" name="email" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('email'))
                                <span class="help-block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('role_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role_id">@lang('users.roles')
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="js-example-basic-multiple form-control" id="role_id" name="role_id[]" multiple>
                                    @if(count($roles))
                                        @foreach($roles as $row)
                                            <option value="{{$row->id}}"
                                                @foreach ($user->roles()->pluck('role_id') as $row_select_id)
                                                    @if($row->id == $row_select_id)
                                                        selected
                                                    @endif
                                                @endforeach
                                                >{{$row->name}}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('role_id'))
                                    <span class="help-block">{{ $errors->first('role_id') }}</span>
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
            $(".js-example-basic-multiple").select2();
            $(".js-example-basic-single").select2();

        });
    </script>
@stop