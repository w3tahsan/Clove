<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    function category(){
        $categories = Category::all();
        return view('admin.category.category', compact('categories'));
    }
    function category_store(CategoryRequest $request){
        $category_image = $request->category_image;
        $extension = $category_image->extension();
        $file_name = uniqid().'.'.$extension;

        $manager = new ImageManager(new Driver());
        $image = $manager->read($category_image);
        $image->resize(200, 200);
        $image->save(public_path('uploads/category/'.$file_name));

        Category::insert([
            'category_name'=>$request->category_name,
            'category_image'=>$file_name,
            'created_at'=>Carbon::now(),
        ]);
        return back()->with('category', 'Category Added successfully');
    }

    function category_delete($category_id){
        Category::find($category_id)->delete();
        return back()->with('soft_del', 'Category move to trash');
    }
    function category_trash(){
        $trash_categories = Category::onlyTrashed()->get();
        return view('admin.category.trash', compact('trash_categories'));
    }
    function category_restore($category_id){
        Category::onlyTrashed()->find($category_id)->restore();
        return back()->with('restore', 'Category Restored success');
    }
    function category_hard_delete($category_id){
        $category = Category::onlyTrashed()->find($category_id);
        $delete_from = public_path('uploads/category/'.$category->category_image);
        unlink($delete_from);
        Category::onlyTrashed()->find($category_id)->forceDelete();
        return back()->with('del', 'Category Deleted Permanantly');
    }
    function check_delete(Request $request){
        foreach($request->category_id as $cat_id){
            Category::find($cat_id)->delete();
        }
        return back()->with('soft_del', 'Category move to trash');
    }
    function check_restore(Request $request){
        if($request->action_btn == 1){
            foreach($request->category_id as $cat_id){
                Category::onlyTrashed()->find($cat_id)->restore();
            }
            return back()->with('restore', 'Category Restored success');
        }
        else{
            foreach($request->category_id as $cat_id){
                $category = Category::onlyTrashed()->find($cat_id);
                $delete_from = public_path('uploads/category/'.$category->category_image);
                unlink($delete_from);
                Category::onlyTrashed()->find($cat_id)->forceDelete();
            }
            return back()->with('del', 'Category Deleted Permanantly');
        }
        
    }
}
