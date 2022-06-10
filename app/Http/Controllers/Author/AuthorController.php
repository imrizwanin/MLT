<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Author;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthorController extends Controller
{
    function create(Request $request){
        //Validate inputs
        // dd($request);
        $request->validate([
           'name'=>'required',
           'email'=>'required|email|unique:authors,email',
           'company'=>'required',
           'password'=>'required|min:5|max:30',
           'cpassword'=>'required|min:5|max:30|same:password'
        ]);

        $author = new Author();
        $author->name = $request->name;
        $author->email = $request->email;
        $author->company = $request->company;
        $author->password = Hash::make($request->password);
        $save = $author->save();

        if( $save ){
            return redirect()->back()->with('success','You are now registered successfully as Author');
        }else{
            return redirect()->back()->with('fail','Something went Wrong, failed to register');
        }
  }

  function check(Request $request){
      //Validate Inputs
      $request->validate([
         'email'=>'required|email|exists:authors,email',
         'password'=>'required|min:5|max:30'
      ],[
          'email.exists'=>'This email is not exists in authors table'
      ]);

      $creds = $request->only('email','password');

      if( Auth::guard('author')->attempt($creds) ){
          return redirect()->route('author.home');
      }else{
          return redirect()->route('author.login')->with('fail','Incorrect Credentials');
      }
  }

  function logout(){
      Auth::guard('author')->logout();
      return redirect('/');
  }
}
