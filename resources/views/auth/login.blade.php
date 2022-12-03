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
</head>

<body class="login">
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form role="form" method="post" action="{{ route('login') }}">
                        <h1>@lang('general.login.title')</h1>
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">                            
                            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="@lang('users.user_name')"/>
                            @if ($errors->has('user_name'))
                            <span class="help-block"><strong>{{ $errors->first('user_name') }}</strong></span>
                            @endif
                        </div>

                      <!--   <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">                            
                            <input type="text" class="form-control" id="email" name="email" placeholder="@lang('general.login.email')"/>
                            @if ($errors->has('email'))
                            <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                            @endif
                        </div> -->
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">                            
                            <input type="password" class="form-control" id="password" name="password" placeholder="@lang('general.login.password')"/>
                            @if ($errors->has('password'))
                            <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                            @endif
                        </div>
                        <div>
                            <button type="submit" class="btn btn-default submit">@lang('general.login.login')</button>
                            <a class="reset_pass" href="{{route('password.reset')}}">@lang('general.login.lost_your_password')</a>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">

                            <div class="clearfix"></div>
                            <br />

                            <div>                                
                                <h1><i class="fa fa-university" style="font-size:36px;"></i>PPS</h1>
                                <p>©2021 All Rights Reserved by <a href="#" target="_blank">ITL - Sri Lanka.</a></p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</body>
</html>
