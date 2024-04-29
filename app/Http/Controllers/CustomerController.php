<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(Request $request)
    {        
        try {
            $authentication  = DB::table('authentication_tokens AS auth')
        //->where('id_user',auth()->user()->id)
        ->where('expired',0)
        ->orderByDesc('created_at')
        ->first();

        if(now()->toDateTimeString() > $authentication->expires_at){
           $expired= DB::table('authentication_tokens')
            ->where('id_authentication_token', $authentication->id_authentication_token)
            ->update(['expired' => 1]);

            if($expired){
                return response()->json(['msg' => 'Unauthorized, expired token' ,'status'=> false], 401);
            }
        }

            if(isset($request->op) && $request->op == 'all'){
                $data = DB::table('customers')
                ->join('communes', 'customers.id_com', '=', 'communes.id_com')
                ->join('regions', 'customers.id_reg', '=', 'regions.id_reg')
                ->where('customers.status', 'A')
                ->selectRaw('name,last_name,IF(address="", NULL, address) AS address,communes.description AS communeDescription,regions.description AS regionDescription')
                ->get();
            }else{
                $data = DB::table('customers')
                ->join('communes', 'customers.id_com', '=', 'communes.id_com')
                ->join('regions', 'customers.id_reg', '=', 'regions.id_reg')
                ->where('customers.status', 'A')
                ->where('dni', $request->param)
                ->orWhere('email',$request->param)
                ->selectRaw('name,last_name,IF(address="", NULL, address) AS address,communes.description AS communeDescription,regions.description AS regionDescription')
                ->get();
            }
                return response()->json(['data' => $data ,'status'=> true], 200);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error data not found',
                'status' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function newCustomer(Request $request){

        $authentication  = DB::table('authentication_tokens AS auth')
        //->where('id_user',auth()->user()->id)
        ->where('expired',0)
        ->orderByDesc('created_at')
        ->first();

        if(now()->toDateTimeString() > $authentication->expires_at){
           $expired= DB::table('authentication_tokens')
            ->where('id_authentication_token', $authentication->id_authentication_token)
            ->update(['expired' => 1]);

            if($expired){
                return response()->json(['msg' => 'Unauthorized, expired token' ,'status'=> false], 401);
            }
        }

        $validator = Validator::make($request->all(),[
            
            'dni'       => 'required|string|max:45',
            'id_reg'    => 'required|integer',
            'id_com'    => 'required|integer',
            'email'     => 'required|email|max:120',
            'name'      => 'required|string|max:45',
            'last_name' => 'required|string|max:45',
            'token'     => 'required',
        ]);
        
        if($validator->fails()){
            $data = [
                'msg'    => 'Error in customer data',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data,400);
        }
        try {
                $commune = DB::table('communes')
                ->where('id_com', $request->id_com)
                ->where('status','A')
                ->first();

                $region = DB::table('regions')
                ->where('id_reg', $request->id_reg)
                ->where('status','A')
                ->first();

                if($commune && $region ){
                    $customer = new Customer();
                    $customer->dni = $request->dni; 
                    $customer->id_reg = $request->id_reg;
                    $customer->id_com = $request->id_com;
                    $customer->email = $request->email;
                    $customer->name = $request->name;
                    $customer->last_name = $request->last_name;
                    $customer->address = $request->address??null;
                    $customer->date_reg= now()->toDateTimeString();
                    $customer->save();
                    return response()->json(['data' => $customer ,'status'=> true], 200);
                }else{
                    return response()->json(['error' =>'commune or regione not related or non-existent'  ,'status'=> false], 404);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'msg' => 'Error when registering customer',
                    'status' => false,
                    'error' => $e->getMessage(),
                ], 500);
            }    
    }

    public function deleteCustomer($dni){
        
        $authentication  = DB::table('authentication_tokens AS auth')
        //->where('id_user',auth()->user()->id)
        ->where('expired',0)
        ->orderByDesc('created_at')
        ->first();

        if(now()->toDateTimeString() > $authentication->expires_at){
           $expired= DB::table('authentication_tokens')
            ->where('id_authentication_token', $authentication->id_authentication_token)
            ->update(['expired' => 1]);

            if($expired){
                return response()->json(['msg' => 'Unauthorized, expired token' ,'status'=> false], 401);
            }
        }
        
        $customerData = DB::table('customers')
        ->where('dni',$dni)
        ->Where('status',3)
        ->first();

        if($customerData){
        return response()->json(['error' =>'Record not found','status'=> false], 404);
        }

        $customer =  DB::table('customers')
        ->where('dni', $dni) 
        ->update(['status' => 3]);
            
        if ($customer){
            return response()->json(['data' => $customer  ,'msg' => 'customer successfully deleted' ,'status'=> true], 200);
        }
        return response()->json(['error' =>'Failed to delete customer','status'=> false], 400);
    }
}
