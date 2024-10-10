@extends('frontend.author.author_main')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3>Edit Author Info</h3>
            </div>
            <div class="card-body">
                @if (session('profile'))
                    <div class="alert alert-success">{{ session('profile') }}</div>
                @endif
                <form action="{{ route('author.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ Auth::guard('author')->user()->username }}">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ Auth::guard('author')->user()->email }}">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Photo</label>
                        <input type="file" name="photo" class="form-control" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                        @error('photo')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                        <div class="my-2">
                            <img id="blah" width="200" src="{{ asset('uploads/author') }}/{{ Auth::guard('author')->user()->photo }}" alt="">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Description</label>
                        <textarea name="desp" rows="5" class="form-control">{{ Auth::guard('author')->user()->desp }}</textarea>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3>Edit Password`</h3>
            </div>
            <div class="card-body">
                @if (session('pass'))
                    <div class="alert alert-success">{{ session('pass') }}</div>
                @endif
                <form action="{{ route('author.pass.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control">
                        @if (session('current'))
                            <strong class="text-danger">{{ session('current') }}</strong>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control">
                        @error('password')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection