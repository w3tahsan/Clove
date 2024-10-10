@extends('admin')
@section('content')
<div class="row">
    @can('users')
    <div class="col-lg-8 ">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="text-white">Users List</h3>
            </div>
            <div class="card-body">
                @if (session('delete'))
                    <div class="alert alert-success">{{ session('delete') }}</div>
                @endif
                <table class="table table-bordered">
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Photo</th>
                        @can('user_del')                            
                        <th>Action</th>
                        @endcan
                    </tr>
                    @foreach ($users as $sl=>$user)
                    <tr>
                        <td>{{ $sl+1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->photo == null)
                                <img src="https://via.placeholder.com/80x80" alt="">
                            @else
                                <img src="{{ asset('uploads/user') }}/{{ $user->photo }}" alt="" width="150">
                            @endif
                        </td>
                        @can('user_del')
                        <td>
                            <form action="{{ route('user.delete', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                        @endcan
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    @endcan
    @can('add_user')
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="text-white">Add New User</h3>
            </div>
            <div class="card-body">
                @if (session('add_user'))
                    <div class="alert alert-success">{{ session('add_user') }}</div>
                @endif
                <form action="{{ route('add.user') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection