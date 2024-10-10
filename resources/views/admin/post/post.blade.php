@extends('admin')
@section('content')
<div class="row">
    @can('post')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3>My Posts</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>SL</th>
                        <th>Author</th>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Published</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($posts as $index=>$post)                        
                    <tr>
                        <td>{{$posts->firstitem()+$index}}</td>
                        <td>{{ $post->rel_to_author->email }}</td>
                        <td>{{ $post->title }}</td>
                        <td><img src="{{ asset('uploads/post/thumbnail') }}/{{ $post->thumbnail }}" alt=""></td>
                        <td>{{ $post->approved ==1?'Yes':'No' }}</td>
                        <td>
                            <a href="{{ route('post.publish', $post->id) }}" class="btn btn-success">Publish</a>
                            <a href="" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
                <div class="my-4">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection