<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{"Visitors Pass Request System"}}</title>
    <link href="{{asset('admin/css/bootstrap.min.css')}}" rel="stylesheet">
    <script src="{{asset('admin/js/jquery.min.js')}}"></script>
    <script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
    <style>

        table, th {
            vertical-align: top;
        }

        #customers {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            font-size: 0.3cm;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
            text-align: left;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #f2f2f2;
            color: black;
        }
    </style>
</head>
<body>

<div class="container">
    <table>
        <tr>
            <th>
                <img src="{{asset('admin/images/itl.png')}}" >
            </th>
            <th>
                <h4>International Trimmings & Labels Lanka (Pvt.) Ltd.</h4>
                <h5>No 272D, Hokandara Road, Thalawatugoda. Sri Lanka.</h5>
                <h5>Tel: +94 11 2775011     Ext:134   I Fax:+94 11 4404978</h5>
            </th>
            <th align="right">
                <h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Details of Visitor Pass Request</h4>
            </th>
        </tr>
        <tr>

        </tr>
    </table>

    <hr>

    <table id="customers">
        <thead>
        <tr>
            <th colspan="2">From Date : {{ $from_date }} </th>
            <th colspan="2">To Date : {{ $to_date }} </th>
            <th colspan="4">Supplier : {{ $supplier_name }}</th>
            <th colspan="2">Status : {{$request_name}}</th>
        </tr>
        <tr>
            <th>@lang('visitorrequestform.request_date')</th>
            <th>@lang('visitorrequestform.request_user')</th>
            <th>
                @lang('visitorrequestform.first_approved') <br>
                @lang('visitorrequestform.second_approved')
            </th>
            <th>
                @lang('visitorrequestform.first_rj_reson') <br>
                @lang('visitorrequestform.second_rj_reson')
            </th>
            <th>@lang('visitorrequestform.supplier_id')</th>
            <th>@lang('visitorrequestform.purpose_of_visit')</th>
            <th>@lang('visitorrequestform.host_from_itl')</th>
            <th>@lang('visitorrequestform.from_date_time')</th>
            <th>@lang('visitorrequestform.act_in_time')</th>
            <th>@lang('visitorrequestform.act_out_time')</th>
        </tr>
        </thead>
        <tbody>
        @if (count($visitorrequestforms))
            @foreach($visitorrequestforms as $row)
                <tr>
                    @if ($row->third_approval == 0)
                        <td class="danger">
                            {{$row->request_date}}<br>{{$row->status_name}}
                        </td>
                    @endif
                    @if ($row->third_approval == 6)
                        <td class="success">
                            {{$row->request_date}}<br>{{$row->status_name}}
                        </td>
                    @endif
                    <td>{{$row->users_name}}</td>
                    <td>{{$row->first_app_name}} <br> {{$row->second_app_name}}</td>
                    <td>{{$row->first_approval_no_reson}} <br> {{$row->second_approval_no_reson}}</td>
                    <td>{{$row->sup_name}}</td>
                    <td>{{$row->purpose_of_visit}}</td>
                    <td>{{$row->host_from_itl}}</td>
                    <td>{{$row->from_date_time}}</td>
                    <td>{{$row->intime_act}}</td>
                    <td>{{$row->outtime_act}} </td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
</div>

</body>
</html>