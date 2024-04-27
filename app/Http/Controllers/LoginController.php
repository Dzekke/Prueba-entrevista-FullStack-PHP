<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;

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
            $userAccount = DB::table('users')
                            ->where('email', $email)
                            ->where('password', $pass)
                            ->where('login', 0)
                            ->whereNull('deleted_at')
                            ->first();

            if ($userAccount){
                $login = DB::table('users')
                ->where('id_user', $userAccount->id_user) 
                ->update(['login' => 1]);

                if ($login) {
                    $token = $this->createToken($email);
                    DB::table('authentication_tokens')->insert([
                        'id_user'       => $userAccount->id_user, 
                        'token'         => $token,
                        'expires_at'    => now()->addMinutes(2),
                    ]);
                }
        
                return response()->json(['message' => 'Login successful','login' => true]);
            } else {
                return response()->json(['error' => 'Incorrect credentials','login' => false], 401);
            }
        } catch (\Exception $e) {
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