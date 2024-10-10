@extends('frontend.master')
@section('content')
    <!--section-heading-->
    <div class="section-heading ">
        <div class="container-fluid">
            <div class="section-heading-2">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-heading-2-title">
                            <h1>{{ $category->category_name }}</h1>
                            <p class="links"><a href="index.html">Home <i class="las la-angle-right"></i></a>
                                {{ $category->category_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Blog Layout-2-->
    <section class="blog-layout-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @foreach ($category_posts as $category_post)
                        <!--post 1-->
                        <div class="post-list post-list-style2 row">
                            <div class="col-lg-4">
                                <div class="post-list-image">
                                    <a href="{{ route('post.details', $category_post->slug) }}">
                                        <img src="{{ asset('uploads/post/thumbnail') }}/{{ $category_post->thumbnail }}"
                                            alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="post-list-content">
                                    <h3 class="entry-title">
                                        <a
                                            href="{{ route('post.details', $category_post->slug) }}">{{ $category_post->title }}</a>
                                    </h3>
                                    <ul class="entry-meta">
                                        <li class="post-author-img">
                                            @if ($category_post->rel_to_author->photo != null)
                                                <img src="{{ asset('uploads/author') }}/{{ $category_post->rel_to_author->photo }}"
                                                    alt="">
                                            @else
                                                <img src="{{ asset('frontend_asset') }}/img/author/1.jpg" alt="">
                                            @endif
                                        </li>
                                        <li class="post-author"> <a
                                                href="{{ route('author.post', $category_post->author_id) }}">{{ $category_post->rel_to_author->username }}</a>
                                        </li>
                                        <li class="entry-cat"> <a
                                                href="{{ route('category.post', $category_post->category_id) }}"
                                                class="category-style-1 "> <span class="line"></span>
                                                {{ $category->category_name }}</a></li>
                                        <li class="post-date"> <span class="line"></span>
                                            {{ $category_post->created_at->diffForHumans() }}</li>
                                    </ul>
                                    <div class="post-btn">
                                        <a href="{{ route('post.details', $category_post->slug) }}"
                                            class="btn-read-more">Continue Reading <i
                                                class="las la-long-arrow-alt-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>


    <!--pagination-->
    <div class="pagination">
        <div class="container-fluid">
            <div class="pagination-area">
                <div class="row">
                    <div class="col-lg-12 category_post">
                        {{ $category_posts->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection