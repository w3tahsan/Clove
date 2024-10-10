<?php

namespace App\Http\Controllers;

use App\Mail\AuthorMailVerify;
use App\Models\Author;
use App\Models\AuthorVerify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthorController extends Controller
{
    function author_store(Request $request)
    {

        $author_id = Author::insertGetId([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => 1,
            'created_at' => Carbon::now(),
        ]);

        // if (Auth::guard('author')->attempt(['email' => $request->email, 'password' => $request->password])) {
        //     return redirect('/');
        // } else {
        //     return back()->with('pass', 'Password incorrect');
        // }

        $author = AuthorVerify::create([
            'author_id' => $author_id,
            'token' => uniqid(),
        ]);

        Mail::to($request->email)->send(new AuthorMailVerify($author));

        return back()->with("verify", "We have sent you a verification email to $request->email! Please verify");

        return back()->with('approval', 'Registration is Pending for Admin Approval! we will notify you through email when your account is approved');
    }

    function author_signin(Request $request)
    {
        if (Author::where('email', $request->email)->exists()) {
            if (Auth::guard('author')->attempt(['email' => $request->email, 'password' => $request->password])) {
                if (Auth::guard('author')->user()->email_verified_at != null) {
                    if (Auth::guard('author')->user()->status != 1) {
                        return back()->with('active', 'Your account is pending for approval! please check email for activation mail');
                    } else {
                        return redirect('/');
                    }
                } else {
                    return back()->with('not_verify', 'Please verify your account first');
                }
            } else {
                return back()->with('pass', 'Password incorrect');
            }
        } else {
            return back()->with('exist', 'Email does not Exist');
        }
    }
    function author_logout()
    {
        Auth::guard('author')->logout();
        return redirect('/');
    }

    function author_dash()
    {
        return view('frontend.author.dash');
    }
    function author_profile()
    {
        return view('frontend.author.edit');
    }
    function author_profile_update(Request $request)
    {
        if ($request->photo == '') {
            Author::find(Auth::guard('author')->id())->update([
                'username' => $request->username,
                'email' => $request->email,
                'desp' => $request->desp,
            ]);
            return back()->with('profile', 'Profile info Updated');
        } else {
            $request->validate([
                'photo' => ['required', 'mimes:jpg,png', 'max:1024'],
            ]);

            if (Auth::guard('author')->user()->photo != null) {
                $delete_from = public_path('uploads/author/' . Auth::guard('author')->user()->photo);
                unlink($delete_from);
            }

            $photo = $request->photo;
            $extension = $photo->extension();
            $file_name = uniqid() . '.' . $extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($photo);
            $image->resize(150, 150);
            $image->save(public_path('uploads/author/' . $file_name));
            Author::find(Auth::guard('author')->id())->update([
                'photo' => $file_name,
                'username' => $request->username,
                'email' => $request->email,
                'desp' => $request->desp,
            ]);
            return back()->with('profile', 'Profile info Updated');
        }
    }
    function author_pass_update(Request $request)
    {
        $request->validate([
            'password' => ['confirmed']
        ]);
        if (Hash::check($request->current_password, Auth::guard('author')->user()->password)) {
            Author::find(Auth::guard('author')->id())->update([
                'password' => bcrypt($request->password),

            ]);
            return back()->with('pass', 'Password Updated');
        } else {
            return back()->with('current', 'Current Password Not matched');
        }
    }
}
