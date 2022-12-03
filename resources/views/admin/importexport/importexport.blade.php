@extends('templates.admin.layout')

@section('content')
<div class="">

    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
               <div class="container">      

                    <div class="panel panel-primary">
                      <div class="panel-heading">
                        <h3 class="panel-title" style="padding:12px 0px;font-size:25px;"><strong>Import Export csv or excel file into database </strong></h3>
                      </div>

                          <div class="panel-body">

                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success" role="alert">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif

                                @if ($message = Session::get('error'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ Session::get('error') }}
                                    </div>
                                @endif

                                <h3>Import File Form:</h3>                               
                                <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 20px;" action="{{route('importexport.importExcel')}}" class="form-horizontal" method="post" enctype="multipart/form-data">

                                    <input type="file" name="import_file" />
                                    {{ csrf_field() }}
                                    <br/>
                                    <button class="btn btn-primary">Import CSV or Excel File</button>
                                </form>
                                <br/>

                                <h3>Import File From Database:</h3>
                                <div style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 20px;">
                                    <a href="{{ url('downloadExcel/xls') }}"><button class="btn btn-success btn-lg">Download Excel xls</button></a>
                                    <a href="{{ url('downloadExcel/xlsx') }}"><button class="btn btn-success btn-lg">Download Excel xlsx</button></a>
                                    <a href="{{ url('downloadExcel/csv') }}"><button class="btn btn-success btn-lg">Download CSV</button></a>
                                </div>
                          </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@stop
