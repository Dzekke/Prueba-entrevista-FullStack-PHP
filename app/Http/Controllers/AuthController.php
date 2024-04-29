<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
      return "Register successfully";  
      $user = new User();
      $user->name = $request->name;  
      $user->email = $request->email;  
      $user->password = Hash::make($request->password);
      $user->save();
      return "Register successfully";  
    }

    // public function login(Request $request){
    //     $credentials = [
    //         'email' => $request->email,
    //         'password' =>$request->password,
    //     ];

    //     if(Auth::attempt($credentials)){
    //         //request()->session()->regenerate();
    //         return redirect('/')->with('success','login successfully');
    //     }
    //     return back()->with('error',' Verify email or password');

    // }
}