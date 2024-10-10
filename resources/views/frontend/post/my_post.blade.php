@extends('frontend.author.author_main')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3>My Posts</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>SL</th>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Published</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($posts as $index=>$post)                        
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $post->title }}</td>
                        <td><img src="{{ asset('uploads/post/thumbnail') }}/{{ $post->thumbnail }}" alt=""></td>
                        <td>{{ $post->approved ==1?'Yes':'No' }}</td>
                        <td>{{ $post->status ==1?'Active':'Deactive' }}</td>
                        <td>
                            <a href="{{ $post->approved ==1?route('post.active', $post->id):'#' }}" class="btn btn-{{ $post->status ==1?'success':'secondary' }}">{{ $post->status ==1?'Active':'Deactive' }}</a>
                            <a href="" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection