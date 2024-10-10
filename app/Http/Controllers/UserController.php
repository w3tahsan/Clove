<?php

namespace App\Http\Controllers;

use App\Mail\AuthorActiveMail;
use App\Mail\AuthorMailVerify;
use App\Models\Author;
use App\Models\AuthorVerify;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function users()
    {
        $users = User::all();
        return view('admin.user.users', compact('users'));
    }
    function authors()
    {
        $authors = Author::all();
        return view('admin.user.authors', [
            'authors' => $authors,
        ]);
    }
    function edit_profile()
    {
        return view('admin.user.edit');
    }
    function update_profile(Request $request)
    {
        User::find(Auth::id())->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return back()->with('success', 'profile Update success');
    }
    function update_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'password_confirmation' => 'required',
        ]);
        if (Hash::check($request->current_password, Auth::user()->password)) {
            User::find(Auth::id())->update([
                'password' => bcrypt($request->password),
            ]);
            return back()->with('pass_update', 'Password Changed successfully');
        } else {
            return back()->with('error_current_pass', 'Current Password Not Matched');
        }
    }
    function update_photo(Request $request)
    {

        $request->validate([
            'photo' => ['required', 'mimes:jpg,png', 'max:1024'],
        ]);

        if (Auth::user()->photo != null) {
            $delete_from = public_path('uploads/user/' . Auth::user()->photo);
            unlink($delete_from);
        }

        $photo = $request->photo;
        $extension = $photo->extension();
        $file_name = uniqid() . '.' . $extension;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($photo);
        $image->resize(150, 150);
        $image->save(public_path('uploads/user/' . $file_name));
        User::find(Auth::id())->update([
            'photo' => $file_name,
        ]);
        return back()->with('photo', 'Profile Photo Updated');
    }

    function user_delete($user_id)
    {
        $user = User::find($user_id);
        if ($user->photo != null) {
            $delete_from = public_path('uploads/user/' . $user->photo);
            unlink($delete_from);
        }
        User::find($user_id)->delete();
        return back()->with('delete', 'User Deleted Success');
    }
    function add_user(Request $request)
    {
        User::insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'created_at' => Carbon::now(),
        ]);
        return back()->with('add_user', 'New User Added success');
    }

    function author_delete($author_id)
    {
        $author = Author::find($author_id);

        if ($author->photo != null) {
            $delete_from = public_path('uploads/author/' . $author->photo);
            unlink($delete_from);
        }

        Author::find($author_id)->delete();
        return back();
    }
    function author_status($author_id)
    {
        $author = Author::find($author_id);

        if ($author->status == 1) {
            Author::find($author_id)->update([
                'status' => 0,
            ]);
            return back();
        } else {
            Author::find($author_id)->update([
                'status' => 1,
            ]);

            Mail::to($author->email)->send(new AuthorActiveMail($author));

            return back();
        }
    }

    function author_verify($token)
    {
        if (AuthorVerify::where('token', $token)->exists()) {
            $author = AuthorVerify::where('token', $token)->first();
            Author::find($author->author_id)->update([
                'email_verified_at' => Carbon::now(),
            ]);
            return redirect()->route('author.login')->with('verified', 'your email is verified');
        } else {
            abort('404');
        }
    }

    function request_verify()
    {
        return view('frontend.author.request_verify');
    }

    function request_verify_send(Request $request)
    {
        $author = Author::where('email', $request->email)->first();

        if (AuthorVerify::where('author_id', $author->id)->exists()) {
            AuthorVerify::where('author_id', $author->id)->delete();
        }

        $author = AuthorVerify::create([
            'author_id' => $author->id,
            'token' => uniqid(),
        ]);
        Mail::to($request->email)->send(new AuthorMailVerify($author));
        return back()->with("verify", "We have sent you a verification email to $request->email! Please verify");
    }
}
