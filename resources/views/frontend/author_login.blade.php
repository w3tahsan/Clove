@extends('frontend.master')
@section('content')
    <!--Login-->
    <section class="login">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-8 m-auto">
                    <div class="login-content">
                        <h4>Login</h4>
                        <p></p>
                        @if (session('active'))
                            <div class="alert alert-danger">{{ session('active') }}</div>
                        @endif
                        @if (session('passreset'))
                            <div class="alert alert-success">{{ session('passreset') }}</div>
                        @endif
                        @if (session('not_verify'))
                            <div class="alert alert-danger">{{ session('not_verify') }} <strong><a
                                        href="{{ route('request.verify') }}" class="badge badge-primary">Request for
                                        Verification mail</a></strong></div>
                        @endif
                        @if (session('verified'))
                            <div class="alert alert-success">{{ session('verified') }}</div>
                        @endif
                        <form action="{{ route('author.signin') }}" class="sign-form widget-form" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Email*" name="email">
                                @if (session('exist'))
                                    <strong class="text-danger">{{ session('exist') }}</strong>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Password*" name="password">
                                @if (session('pass'))
                                    <strong class="text-danger">{{ session('pass') }}</strong>
                                @endif
                            </div>
                            <div class="sign-controls form-group">
                                <a href="{{ route('pass.reser.req') }}" class="btn-link ">Forgot Password?</a>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn-custom">Login in</button>
                            </div>
                            <p class="form-group text-center">Don't have an account? <a
                                    href="{{ route('author.register') }}" class="btn-link">Create One</a> </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
