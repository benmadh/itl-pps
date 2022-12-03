@extends('templates.admin.layout')

@section('content')
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('general.nav.change_password')</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('auth.change_password') }}" data-parsley-validate class="form-horizontal form-label-left">
                    
                     <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">                  
                       <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Current Password" required="required">

                       @if ($errors->has('current_password'))
                           <span class="help-block">
                               <strong>{{ $errors->first('current_password') }}</strong>
                           </span>
                       @endif
                    </div>

                    <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">                  
                       <input type="password" class="form-control" name="new_password" id="new_password" placeholder="New Password" required="required">

                       @if ($errors->has('new_password'))
                           <span class="help-block">
                               <strong>{{ $errors->first('new_password') }}</strong>
                           </span>
                       @endif
                    </div>
                   

                    <div class="form-group{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                       <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirm Password" required="required">

                       @if ($errors->has('new_password_confirmation'))
                           <span class="help-block">
                               <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                           </span>
                       @endif
                   </div>

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
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