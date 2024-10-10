<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Popular;
use App\Models\Post;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    function all_post(){
        $posts = Post::orderBy('approved', 'ASC')->paginate(3);
        return view('admin.post.post', [
            'posts'=>$posts,
        ]);
    }
    function post_publish($post_id){
        Post::find($post_id)->update([
            'approved'=>1,
            'status'=>1,
        ]);
        return back();
    }
    function add_post(){
        $categories = Category::all();
        $tags = Tag::all();
        return view('frontend.post.add_post', [
            'categories'=>$categories,
            'tags'=>$tags,
        ]);
    }
    function post_store(Request $request){

        //thumbnail
        $thumbnail = $request->thumbnail;
        $extension = $thumbnail->extension();
        $thumbnail_name = uniqid().'.'.$extension;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($thumbnail);
        $image->resize(250, 200);
        $image->save(public_path('uploads/post/thumbnail/'.$thumbnail_name));

        //Preview
        $preview = $request->preview;
        $extension = $preview->extension();
        $preview_name = uniqid().'.'.$extension;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($preview);
        $image->resize(1920, 800);
        $image->save(public_path('uploads/post/preview/'.$preview_name));

        Post::insert([
            'author_id'=>Auth::guard('author')->id(),
            'category_id'=>$request->category_id,
            'read_time'=>$request->read_time,
            'title'=>$request->title,
            'slug'=>Str::lower(str_replace(' ', '-', $request->title)).'-'.random_int(10000, 900000),
            'desp'=>$request->desp,
            'tag_id'=>implode(',', $request->tag_id),
            'thumbnail'=>$thumbnail_name,
            'preview'=>$preview_name,
            'created_at'=>Carbon::now(),
        ]);
        return back();
    }
    function my_post(){
        $posts = Post::where('author_id', Auth::guard('author')->id())->get();
        return view('frontend.post.my_post', [
            'posts'=>$posts,
        ]);
    }
    function post_active($post_id){
        $post = Post::find($post_id);
        if($post->status == 1){
            Post::find($post_id)->update([
                'status'=>0,
            ]);
            return back();
        }
        else{
            Post::find($post_id)->update([
                'status'=>1,
            ]);
            return back();
        }
    }
    function post_details($slug){
        $post = Post::where('slug', $slug)->get();
        $post = Post::find($post->first()->id);
        $post_id = Post::where('slug', $slug)->first()->id;
     
        if(Popular::where('post_id', $post_id)->exists()){
            Popular::where('post_id', $post_id)->increment('total', 1);
        }
        else{
            Popular::insert([
                'post_id'=>$post_id,
                'total'=>1,
            ]);
        }

        $comments = Comment::with('replies')->where('post_id', $post_id)->whereNull('parent_id')->get();
        $total_comments = Comment::where('post_id', $post_id)->count();

        return view('frontend.post.details', [
            'post'=>$post,
            'comments'=>$comments,
            'total_comments'=>$total_comments,
        ]);
    }
    function author_post($author_id){
        $posts = Post::where('author_id', $author_id)->where('status', 1)->paginate(1);
        $author_info = Author::find($author_id);
        return view('frontend.post.author_post', [
            'posts'=>$posts,
            'author_info'=>$author_info,
        ]);
    }

    function category_post($category_id){
        $category_posts = Post::where('category_id', $category_id)->where('status', 1)->where('approved', 1)->paginate(1);
        $category = Category::find($category_id);
        return view('frontend.post.category_post', [
            'category_posts'=>$category_posts,
            'category'=>$category,
        ]);
    }

    function tag_post($tag_id){
        $tag = Tag::find($tag_id);
        $all = [];
        foreach(Post::all() as $post){
            $after_explode = explode(',', $post->tag_id);
            if(in_array($tag_id, $after_explode)){
                array_push($all, $post->id);
            }
        }
        $tag_posts = Post::whereIn('id', $all)->paginate(1);
        return view('frontend.post.tag_post', [
            'tag'=>$tag,
            'tag_posts'=>$tag_posts,
        ]);
    }
}
