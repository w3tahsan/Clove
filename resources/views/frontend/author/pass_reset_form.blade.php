@extends('frontend.master')
@section('content')
    <section class="login">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-8 m-auto">
                    <div class="login-content">
                        <h4>Password Reset Form</h4>
                        <p></p>
                        @if (session('active'))
                            <div class="alert alert-danger">{{ session('active') }}</div>
                        @endif

                        <form action="{{ route('pass.reset.update', $token) }}" class="sign-form widget-form" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="New Password*" name="password">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Confirm Password*"
                                    name="password_confirmation">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn-custom">Update Password</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
