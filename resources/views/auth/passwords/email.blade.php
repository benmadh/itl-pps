<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ITL Admin Login</title>
    <link rel="shortcut icon" type="image/png" href="{{asset('admin/images/favicon.png')}}"/>

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
        <a class="hiddenanchor" id="signup"></a>
        <a class="hiddenanchor" id="lostpassword"></a>

        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                    @if (Session::has('error'))
                        <div class="alert alert-danger">
                            <ul>
                                <li>{!! Session::get('error') !!}</li>
                            </ul>
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                        <h1>@lang('general.passwords.title')</h1>
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
                            @if ($errors->has('user_name'))
                                <span class="help-block"><strong>{{ $errors->first('user_name') }}</strong></span>
                            @endif
                            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Username"/>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-default submit">@lang('general.passwords.send_password_link')</button>
                            <a class="reset_pass" href="{{route('login')}}">@lang('general.login.login')</a>
                        </div>

                        <div class="clearfix"></div>
                        <div class="separator">
                            <div class="clearfix"></div>
                            <br />
                            <div>                                
                                <h1><i class="fa fa-university" style="font-size:36px;"></i>ITL Admin Panel</h1>
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
