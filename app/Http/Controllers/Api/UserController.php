<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /*
     * USER REGISTER API - POST
     */
    public function register(Request $request)
    {

        /*
         * Validation
         */
        $request->validate([

            'name'=>'required',
            'email'=>'required|email|unique:users',
            'phone_no'=>'required',
            'password'=>'required|confirmed'

        ]);



        /*
         * Create user data + save
         */
        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->phone_no=$request->phone_no;
        $user->password=bcrypt($request->password); // bcrypt method is for encrypting password in Hash format

        $user->save();

        /*
         * send response
         */

        return response()->json([

            'status'=>1,
            'message'=>'User registered successfully!'

        ],200);

    }

    /*
   * USER LOGIN API - POST
   */
    public function login(Request $request)
    {
        /*
         * Validation
         */
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);




        /*
         * Verify User + token
         * "auth()" helper function is accessed via ['middleware'=>['auth:api']]
         */

        $token=auth()->attempt([ //"auth()" helper function is accessed via ['middleware'=>['auth:api']]

            'email'=> $request->email,
            'password'=>$request->password
        ]);

        if (!$token){

            return response()->json([
                'status'=>0,
                'message'=>'Invalid Credential'
            ]);

        }

        /*
         * Send response
         */

        return response()->json([
            'status'=>1,
            'message'=>'Logged In successfully',
            'token'=>$token
        ]);


    }

    /*
     * USER PROFILE API - GET
     */
    public function profile()
    {
        $user_data=auth()->user(); // "auth()" helper function is accessed via ['middleware'=>['auth:api']]

        return response()->json([

            "status" => 1,
            "message" => "User profile data",
            "data" => $user_data

        ]);


    }

    /*
     * USER LOGOUT API - GET
     */
    public function logout()
    {

        auth()->logout();
        return response()->json([

            'status'=>1,
            'message'=>'User logged out'

        ]);

    }
}
