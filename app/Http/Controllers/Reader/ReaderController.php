<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Reader;
use App\Models\VerifyReader;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ReaderController extends Controller
{
    function create(Request $request){
        //Validate Inputs
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:readers,email',
            'password'=>'required|min:5|max:30',
            'cpassword'=>'required|min:5|max:30|same:password'
        ]);

        $reader = new Reader();
        $reader->name = $request->name;
        $reader->email = $request->email;
        $reader->password = Hash::make($request->password);
        $save = $reader->save();
        $last_id=$reader->id;
        $token = $last_id.hash('md5',Str::random(200));

        if( $save ){
            return redirect()->back()->with('success','You are now registered successfully');
        }else{
            return redirect()->back()->with('fail','Something went wrong, failed to register');
        }
  }

  function check(Request $request){
      //Validate inputs
      $request->validate([
         'email'=>'required|email|exists:readers,email',
         'password'=>'required|min:5|max:30'
      ],[
          'email.exists'=>'This email is not exists on users table'
      ]);

      $creds = $request->only('email','password');
      if( Auth::guard('reader')->attempt($creds) ){
          return redirect()->route('reader.home');
      }else{
          return redirect()->route('reader.login')->with('fail','Incorrect credentials');
      }
  }
  public function verify(Request $request)
  {
      $token = $request->token;
      $verifyReader = VerifyReader::where('token',$token)->first();
      if(!is_null($verifyReader)){
          $reader = $$verifyReader->reader;

          if(!$reader->email_verified){
              $verifyReader->reader->email_verified=1;
              $verifyReader->reader->email_verified_at=Carbon::now();
              $verifyReader->reader->save();
              return redirect()->route('user.login')->with('info','Email Verified')->with('verifiedEmail',$reader->email);
          }else{
            return redirect()->route('user.login')->with('info','Email Already Verified')->with('verifiedEmail',$reader->email);
          }
      }
  }

  function logout(){
      Auth::guard('reader')->logout();
      return redirect('/');
  }
}
