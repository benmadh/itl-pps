@extends('templates.admin.layout')
@section('content')
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-11 col-sm-11 col-xs-11">
                            <h2>@lang('general.app.confirm.delete.title')</h2>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <h2><a href="{{route('items.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a></h2>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p>@lang('general.app.confirm.delete.question') <strong>{{$dataObj->name}} ?</strong></p>
                    <form method="POST" action="{{ route('items.destroy', ['id' => $dataObj->id]) }}">
                        <input type="hidden" name="_token" value="{{ Session::token() }}">
                        <input name="_method" type="hidden" value="DELETE">
                        <button type="submit" class="btn btn-danger">@lang('general.form.delete') <strong>{{$dataObj->name}}</strong></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop