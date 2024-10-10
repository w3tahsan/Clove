@extends('admin')
@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="text-white">Edit Profile</h3>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form action="{{ route('update.profile') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="text-white">Update Password</h3>
            </div>
            <div class="card-body">
                @if (session('pass_update'))
                    <div class="alert alert-success">{{ session('pass_update') }}</div>
                @endif
                <form action="{{ route('update.password') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="">Current Password</label>
                        <input type="password" name="current_password" class="form-control">
                        @error('current_password')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                        @if (session('error_current_pass'))
                            <strong class="text-danger">{{ session('error_current_pass') }}</strong>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="">New Password</label>
                        <input type="password" name="password" class="form-control">
                        @error('password')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                        @error('password_confirmation')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="text-white">Update Photo</h3>
            </div>
            <div class="card-body">
                @if (session('photo'))
                    <div class="alert alert-success">{{ session('photo') }}</div>
                @endif
                <form action="{{ route('update.photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Upload Photo</label>
                        <input type="file" name="photo" class="form-control" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                        <div class="my-2">
                            <img src="{{ asset('uploads/user') }}/{{ Auth::user()->photo }}" id="blah" alt="" width="200">
                        </div>
                        @error('photo')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update Photo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection