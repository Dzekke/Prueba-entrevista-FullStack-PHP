<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class LoginController extends Controller
{

    public function login(Request $request)
    { 
        $validator = Validator::make($request->all(),[
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        
        if($validator->fails()){
            $data = [
                'msg'    => 'Error in data validation',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data,400);
        }
        $email = $request->email;
        $pass = $request->password;
        try {
            $credentials = $request->validate([
                'email' => ['required','string','email'],
                'password' =>['required','string']
            ]);

            $user = User::where('email','=',$email)->first();
            if($user && hash::check($pass, $user->password)){
                Auth::attempt($credentials);
                //Auth::login($user);
                if(Auth::check()){
                    $currentUser = auth()->user();
                    
                    $token = $this->createToken($email);
                    DB::table('authentication_tokens')->insert([
                        'id_user'       => $currentUser->id, 
                        'token'         => $token,
                        'expires_at'    => now()->addMinutes(2),
                    ]);
                return response()->json(['message' => 'Successful login','login' => true, 'user' => $currentUser]);
                }else{
                return response()->json(['error' => 'error in Auth::check ','login' => false], 401);
                }
            }else{
                return response()->json(['error' => 'Incorrect credentials','login' => false], 401);
            }           
        }catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error in login',
                'status' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function createToken($email)
    {
        $timestamp = now()->toDateTimeString();
        $random = mt_rand(200, 500);
        $data = $email . $timestamp . $random;
        $token = Hash::make($data);

        return $token;
    }

}