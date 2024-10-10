<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Popular;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    function index()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $sliders = Post::where('status', 1)->latest()->take(3)->get();
        $posts = Post::where('status', 1)->latest()->paginate(2);
        $populars = Popular::where('total', '>=', 20)->get();
        return view('frontend.index', [
            'categories' => $categories,
            'tags' => $tags,
            'sliders' => $sliders,
            'posts' => $posts,
            'populars' => $populars,
        ]);
    }
    function author_register()
    {
        return view('frontend.author_register');
    }
    function author_login()
    {
        return view('frontend.author_login');
    }
    function search(Request $request)
    {
        $data  = $request->all();
        $search_posts = Post::where(function ($q) use ($data) {
            if (!empty($data['keyword']) && $data['keyword'] != '' && $data['keyword'] != 'undefined') {
                $q->where(function ($q) use ($data) {
                    $q->where('title', 'like', '%' . $data['keyword'] . '%');
                    $q->orWhere('desp', 'like', '%' . $data['keyword'] . '%');
                });
            }
        })->get();
        return view('frontend.search', [
            'search_posts' => $search_posts,
        ]);
    }

    function comment_store(Request $request, $author_id)
    {
        $comments = Comment::create([
            'author_id' => $author_id,
            'post_id' => $request->post_id,
            'parent_id' => $request->parent_id,
            'comments' => $request->comments,
        ]);

        return back();
    }

    function author_list()
    {
        $authors = Author::where('status', 1)->paginate(2);
        return view('frontend.author_list', [
            'authors' => $authors,
        ]);
    }
}
