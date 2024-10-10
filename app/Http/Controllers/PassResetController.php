<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\PassReset;
use App\Notifications\PassResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PassResetController extends Controller
{
    function pass_reset_req()
    {
        return view('frontend.author.pass_reset_req');
    }

    function pass_reset_req_send(Request $request)
    {
        $author = Author::where('email', $request->email)->first();
        if (Author::where('email', $request->email)->exists()) {
            if (PassReset::where('author_id', $author->id)->exists()) {
                PassReset::where('author_id', $author->id)->delete();
            }
            $author_info = PassReset::create([
                'author_id' => $author->id,
                'token' => uniqid(),
            ]);

            Notification::send($author, new PassResetNotification($author_info));
            return back()->with('success', "We have sent you a password reset link to $request->email");
        } else {
            return back()->with('exist', 'Email Does Not Exists');
        }
    }

    function pass_reset_form($token)
    {
        if(PassReset::where('token', $token)->exists()){
            return view('frontend.author.pass_reset_form', [
                'token' => $token,
            ]);
        }
        else{
            abort('404');
        }
        
    }

    function pass_reset_update(Request $request, $token)
    {
        $author = PassReset::where('token', $token)->first();
        if (PassReset::where('token', $token)->exists()) {
            Author::find($author->author_id)->update([
                'password' => bcrypt($request->password),
            ]);

            PassReset::where('token', $token)->delete();

            return redirect()->route('author.login')->with('passreset', 'Password Reset Successfully');
        } else {
            return redirect()->route('pass.reser.req')->with('link', 'Invalid Password Reset Link');
        }
    }
}
