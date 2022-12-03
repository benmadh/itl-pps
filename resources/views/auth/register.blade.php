<!DOCTYPE html>
<html lang="en">
  <head>
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Production Planning System</title>   
    <link rel="shortcut icon" type="image/png" href="{{asset('admin/images/itlicon.ico')}}"/>
    <!-- Bootstrap -->
    <link href="{{asset('admin/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('admin/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('admin/css/nprogress.css')}}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{asset('admin/css/animate.min.css')}}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{asset('admin/css/custom.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/select2/select2.min.css')}}" rel="stylesheet">    
  </head>
  <body class="login">
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                  <form role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                          <h1>Registration Form</h1>
                          <p>Create your account. It's free and only takes a minute. CS Department Only</p>
                          <br/> 
                      
                      <div class="form-group{{ $errors->has('employeeid') ? ' has-error' : '' }}">
                          <select class="js-example-basic-single form-control" id="employeeid" name="employeeid">
                              @if(count($employees))
                                  <option value="" disabled selected>EPF No</option>
                                  @foreach($employees as $row)
                                      <option value="{{$row->id}}">{{$row->epfno}} / {{$row->name}}</option>
                                  @endforeach
                              @endif
                          </select>
                          @if ($errors->has('employeeid'))
                              <span class="help-block">{{ $errors->first('employeeid') }}</span>
                          @endif                           
                      </div>

                      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}"> 
                         <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="Email" required="required">
                         @if ($errors->has('email'))
                             <span class="help-block">
                                 <strong>{{ $errors->first('email') }}</strong>
                             </span>
                         @endif
                     </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">                  
                       <input type="password" class="form-control" name="password" id="password" placeholder="Password" required="required">

                       @if ($errors->has('password'))
                           <span class="help-block">
                               <strong>{{ $errors->first('password') }}</strong>
                           </span>
                       @endif
                    </div>

                    <div class="form-group{{ $errors->has('password-confirm') ? ' has-error' : '' }}">
                       <input type="password" class="form-control" name="password_confirmation" id="password-confirm" placeholder="Confirm Password" required="required">

                       @if ($errors->has('password-confirm'))
                           <span class="help-block">
                               <strong>{{ $errors->first('password-confirm') }}</strong>
                           </span>
                       @endif
                   </div>
                    
                    <div class="form-group">                 
                           <button type="submit" class="btn btn-default submit">Register Now</button>
                           <div class="text-center">Already have an account? <a href="{{ route('login') }}">Sign in</a> </div>              
                   </div>
                    
                    <div class="clearfix"></div>

                    <div class="separator">
                        <div class="clearfix"></div>
                        <br />
                        <div>                                
                            <h1><i class="fa fa-university" style="font-size:36px;"></i>ITL - PPS</h1>
                            <p>©2017 All Rights Reserved by <a href="#" target="_blank">ITL - Thalawathugoda.</a></p>
                        </div>
                    </div>
          
              </form>
      
                </section>
            </div>
        </div>
    </div> 
    <!-- jQuery -->
<script src="{{asset('admin/js/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('admin/js/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('admin/js/nprogress.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('admin/js/icheck.min.js')}}"></script>
<!-- Datatables -->
<script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('admin/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('admin/js/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('admin/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('admin/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('admin/js/buttons.print.min.js')}}"></script>
<script src="{{asset('admin/js/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('admin/js/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('admin/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin/js/responsive.bootstrap.js')}}"></script>
<script src="{{asset('admin/js/datatables.scroller.min.js')}}"></script>
<script src="{{asset('admin/js/jszip.min.js')}}"></script>
<script src="{{asset('admin/js/pdfmake.min.js')}}"></script>
<script src="{{asset('admin/js/vfs_fonts.js')}}"></script>
<script src="{{asset('admin/css/select2/select2.full.min.js')}}"></script>
<script src="{{asset('admin/js/bootstrap-checkbox.min.js')}}"></script>
<!-- Custom Theme Scripts -->
<script src="{{asset('admin/js/custom.min.js')}}"></script>

<!-- Add by dimuth 27-07-2017 bootstrap-datepicker -->
<script src="{{asset('admin/css/daterangepicker/daterangepicker.js')}}" type="text/javascript" ></script>
<script src="{{asset('admin/css/datepicker/bootstrap-datepicker.js')}}" type="text/javascript" ></script>
<script src="{{asset('admin/js/moment-2.4.0.js')}}"></script>
<script src="{{asset('admin/js/bootstrap-datetimepicker.js')}}" type="text/javascript" ></script>
<script src="{{asset('admin/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript" ></script>


<script src="{{asset('admin/js/jquery.minicolors.js')}}" type="text/javascript" ></script>

<!-- icheck checkboxes -->
<script type="text/javascript" src="{{ asset('admin/icheck/icheck.min.js') }}"></script>
    <script>
        $(document).ready(function() { 
            $(".js-example-basic-multiple").select2();
            $(".js-example-basic-single").select2();
        });
    </script>   
  </body>
</html>


