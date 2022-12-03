@extends('templates.admin.layout')

@section('content')
    <div class="">        
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                 @include('general.templates.quck_link_screen')
                <div class="x_panel">
                    <div class="x_title">                     
                        <h2>Dash Board - Screen</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <br />
                        <form method="post" action="{{ route('dbScreen.chartDetails') }}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                               <div class="row">
                                    <div class ="col-md-4">
                                        <div class="x_panel">
                                            <div class="form-group">
                                                <label class="control-label col-md-5 col-sm-5 col-xs-5" for="fromdate">From Date <span class="required">*</span>
                                                </label>
                                                <div class="col-md-7 col-sm-7 col-xs-7">
                                                  <div class="input-group date margin-bottom: 0px;">
                                                    <input type="text" value="{{ $fromdate }}" id="fromdate" name="fromdate" class="form-control col-md-7 col-xs-12">
                                                    @if ($errors->has('fromdate'))
                                                    <span class="help-block">{{ $errors->first('fromdate') }}</span>
                                                    @endif
                                                    <div class="input-group-addon">
                                                      <i class="fa fa-calendar"></i>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                    <div class ="col-md-4">
                                        <div class="x_panel">
                                            <div class="form-group">
                                                <label class="control-label col-md-5 col-sm-5 col-xs-5" for="todate">To Date <span class="required">*</span>
                                                </label>
                                                <div class="col-md-7 col-sm-7 col-xs-7">
                                                  <div class="input-group date margin-bottom: 0px;">
                                                    <input type="text" value="{{ $todate }}" id="todate" name="todate" class="form-control col-md-7 col-xs-12">
                                                    @if ($errors->has('todate'))
                                                    <span class="help-block">{{ $errors->first('todate') }}</span>
                                                    @endif
                                                    <div class="input-group-addon">
                                                      <i class="fa fa-calendar"></i>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                    <div class ="col-md-4">
                                        <div class="x_panel">
                                            <div class="form-group">
                                               <input type="hidden" name="_token" value="{{ Session::token() }}">
                                               <button type="submit" class="btn btn-success"> <i class="fa fa-filter" aria-hidden="true"></i>   Filter    </button>

                                            </div>
                                       </div>
                                    </div>
                               </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="x_panel">                   
                       <div class="form-group">                             
                           <div class="app">
                                <left>
                                    {!! $oee_chart->html() !!}
                                </left>
                            </div>                           
                        </div>                             
                    </div>
                </div>               
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="x_panel">                   
                       <div class="form-group">                             
                            <div class="app">
                                <center>
                                   {!! $quality_chart->html() !!}
                                </center>
                            </div>                           
                        </div>                             
                    </div>
                </div>
            </div>            
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="x_panel">                   
                       <div class="form-group">                             
                           <div class="app">
                                <left>
                                    {!! $chart->html() !!}
                                </left>
                            </div>                           
                        </div>                             
                    </div>
                </div>               
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="x_panel">                   
                       <div class="form-group">                             
                            <div class="app">
                                <center>
                                   {!! $chart2->html() !!}
                                </center>
                            </div>                           
                        </div>                             
                    </div>
                </div>
            </div>            
        </div>

    </div>  
@stop
@section ('js')
 <script>
    $('#fromdate').datepicker({
        autoclose: true,
        format: 'yyyy/mm/dd'
    });

    $('#todate').datepicker({
        autoclose: true,
        format: 'yyyy/mm/dd'
    });
 </script> 
 {!! Charts::scripts() !!}
 {!! $oee_chart->script() !!}
 {!! $quality_chart->script() !!}
 {!! $chart->script() !!}
 {!! $chart2->script() !!}
@stop
