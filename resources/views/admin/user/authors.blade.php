@extends('admin')
@section('content')
@can('author')
<div class="row">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h3>Authors List</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>SL</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Photo</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($authors as $index=>$author)                        
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $author->username }}</td>
                        <td>{{ $author->email }}</td>
                        <td>
                            @if ($author->photo == null)
                                <img src="https://via.placeholder.com/30x30" alt="userr">
                            @else    
                            <img width="100" src="{{ asset('uploads/author') }}/{{ $author->photo }}" alt="">
                            @endif
                        </td>
                        <td>
                            <a class="badge badge-{{ $author->status==1?'success':'light' }}">{{ $author->status==1?'Active':'Deactive' }}</a>
                        </td>
                        <td>
                            @can('author_status')
                            <a href="{{ route('author.status', $author->id) }}" class="btn btn-primary">{{ $author->status==1?'Dactive Author':'Active Author' }}</a>
                            @endcan
                            @can('author_del')                                
                            <a href="{{ route('author.delete', $author->id) }}" class="btn btn-danger">Delete</a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@else
<h2 class="text-danger">There are almost 8 Billion people in the world but only few have access to this page, unfortunately you are not one of them</h2>
@endcan
@endsection