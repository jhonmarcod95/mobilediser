<hmtl>
    <head>
        @include('layouts.head')
    </head>

    <body class="hold-transition login-page">


    <div class="login-box">
        <div class="login-logo">
            <b>PLILI</b> <br> MobileDiser Portal
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form class="form-signin" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="form-group has-feedback">
                    <input id="inputEmail" type="email" class="form-control" name="email" placeholder="Email"
                           value="{{ old('email') }}" required autofocus>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
                    <input id="inputPassword" type="password" class="form-control" name="password"
                           placeholder="Password"
                           required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                    @if ($errors->has('email'))
                        <span class="help-block" style="color: red">
                        {{ $errors->first('email') }}
                    </span>
                    @endif

                    @if ($errors->has('password'))
                        <span class="text-danger">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>


                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox check">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember
                                Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <!-- /.social-auth-links -->
            {{--<a href="{{ route('password.request') }}">--}}
                {{--I forgot my password--}}
            {{--</a>--}}
        </div>
        @if(Session::has('message'))
            <div class="alert alert-danger" role="alert">
                {{ Session::get('message') }}
            </div>
            {{ session()->flush() }}
        @endif
    </div>




    @include('layouts.script')
    </body>
</hmtl>
