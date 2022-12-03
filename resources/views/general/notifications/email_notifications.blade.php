<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css" rel="stylesheet" media="all">
        /* Media Queries */
        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }


        #details-line {
          font-family: Verdana, Geneva, sans-serif;
          font-size: 10pt;
          border-collapse: collapse;
          width: 80%;
          }

          #details-line td, #details-line th {
              border: 1px solid #ddd;
              text-align: left;
              padding: 6px;
              vertical-align: top;
          }

          #details-line tr:nth-child(even){background-color: #f2f2f2;}

          #details-line tr:hover {background-color: #ddd;}

          #details-line th {
              padding-top: 6px;
              padding-bottom: 6px;
              text-align: left;
              background-color: #92a8d1;
              color: white;
          }  
    </style>
</head>

<?php

$style = [
    /* Layout ------------------------------ */

    'body' => 'margin: 0; padding: 0; width: 100%; background-color: #F2F4F6;',
    'email-wrapper' => 'width: 100%; margin: 0; padding: 0; background-color: #F2F4F6;',

    /* Masthead ----------------------- */

    'email-masthead' => 'padding: 25px 0; text-align: left;',
    'email-masthead_name' => 'font-size: 16px; font-weight: bold; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white;',

    'email-body' => 'width: 100%; margin: 0; padding: 0; border-top: 1px solid #EDEFF2; border-bottom: 1px solid #EDEFF2; background-color: #FFF;',
    'email-body_inner' => 'width: auto; max-width: 570px; margin: 0 auto; padding: 0;',
    'email-body_cell' => 'padding: 35px;',

    'email-footer' => 'width: auto; max-width: 570px; margin: 0 auto; padding: 0; text-align: center;',
    'email-footer_cell' => 'color: #AEAEAE; padding: 35px; text-align: center;',

    /* Body ------------------------------ */

    'body_action' => 'width: 100%; margin: 30px auto; padding: 0; text-align: center;',
    'body_sub' => 'margin-top: 25px; padding-top: 25px; border-top: 1px solid #EDEFF2;',

    /* Type ------------------------------ */

    'anchor' => 'color: #3869D4;',
    'header-1' => 'margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold; text-align: left;',
    'paragraph' => 'margin-top: 0; color: #74787E; font-size: 16px; line-height: 1.5em;',
    'paragraph-sub' => 'margin-top: 0; color: #74787E; font-size: 12px; line-height: 1.5em;',
    'paragraph-center' => 'text-align: center;',

    /* Buttons ------------------------------ */

    'button' => 'display: block; display: inline-block; width: 200px; min-height: 20px; padding: 10px;
                 background-color: #3869D4; border-radius: 3px; color: #ffffff; font-size: 15px; line-height: 25px;
                 text-align: center; text-decoration: none; -webkit-text-size-adjust: none;',

    'button--green' => 'background-color: #22BC66;',
    'button--red' => 'background-color: #dc4d2f;',
    'button--blue' => 'background-color: #3869D4;',      
];
?>

<?php $fontFamily = 'font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;'; ?>

<body style="{{ $body_style }}">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $email_wrapper }}" align="center">
                <table width="85%" cellpadding="0" cellspacing="0">
                    <!-- Logo -->
                    <tr>
                        <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }}">
                            
                                 <h4 style="{{ $style['header-1'] }}">
                                      {{ $notification_masthead }}
                                 </h4>
                                <!-- <img src="{{asset('admin/images/itl.png')}}" > --> 
                                @if($mail_method == 2)
                                    <p style="{{ $style['paragraph'] }}">
                                       {{ $hod_comments }} <br>                                 
                                       {{ $hod_rgs }}<br>
                                       {{ $dep_hod_name }}                                       
                                    </p> 
                                @endif
                                @if($mail_method == 3)
                                    <p style="{{ $style['paragraph'] }}">
                                       {{ $hod_comments }} <br>                                 
                                       {{ $hod_rgs }}<br>
                                       Management                                       
                                    </p> 
                                @endif
                        </td>
                    </tr>
                    
                    <!-- Email Body -->
                    <tr>
                        <td style="{{ $style['email-body'] }}" width="85%">
                            <table style="{{ $style['email-body_inner'] }}" align="center" width="85%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }}">
                                        <!-- Greeting -->
                                        <h1 style="{{ $style['header-1'] }}">
                                            Hi, {{ $dep_hod_name }}
                                        </h1>

                                        <!-- Intro -->                                        
                                            <p style="{{ $style['paragraph'] }}">
                                                Please approve this request to enable <strong> {{ $suppliers_name }} </strong> to visit us on <strong>{{ $from_date_time }}</strong> to <strong>{{ $to_date_time }}</strong> to / for <strong>{{ $purpose_of_visit }}.</strong> They will be visiting <strong>{{ $host_from_itl }}.</strong>
                                            </p> 
  
                                        <!-- Action Button -->
                                                                                 
                                             <table id="details-line" width="80%">
                                                <thead>
                                                  <tr>
                                                    <th colspan="3">Visitor details as follows </th>
                                                  </tr>
                                                  <tr>            
                                                    <th>Name</th>
                                                    <th>NIC</th>
                                                    <th>Contact No</th>
                                                  </tr>
                                                </thead>
                                               <tbody>
                                                  @if (count($linedetails))
                                                    @foreach($linedetails as $row)
                                                      <tr>
                                                        <td>{{ $row->name }}</td>
                                                        <td>{{ $row->nic }}</td>                         
                                                        <td>{{ $row->contact_no }}</td>                            
                                                      </tr>
                                                    @endforeach
                                                   @endif                                                   
                                              </tbody>
                                              
                                            </table>
                                               
                                        <!-- Salutation -->
                                        <p style="{{ $style['paragraph'] }}">
                                            Regards,<br>{{ $regards }} 
                                        </p>

                                        <table style="{{ $style['body_sub'] }}">
                                                <tr>
                                                    <td style="{{ $fontFamily }}">
                                                        <p style="{{ $style['paragraph-sub'] }}">
                                                            This is a system generated mail. Please DO NOT Reply
                                                        </p>                                                       
                                                    </td>
                                                </tr>
                                            </table>
                                                                               
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td>
                            <table style="{{ $style['email-footer'] }}" align="center" width="85%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="{{ $fontFamily }} {{ $style['email-footer_cell'] }}">
                                        <p style="{{ $style['paragraph-sub'] }}">
                                            &copy; {{ date('Y') }}
                                            <a style="{{ $style['anchor'] }}" href="{{ url('/') }}" target="_blank">ITL</a>.
                                            All rights reserved.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
