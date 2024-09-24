<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; /*for login*/

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $response = array('check'=> 'failed' , 'message'=> 'Something Error');//,'err_code'=>500

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|numeric|digits:10|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);


        if ($validator->fails()) {
            $response['message'] = 'validation error';
            $response['error'] = $validator->errors();
            $err_code = 422;
            return response()->json($response, $err_code);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);
        if($user){
            $response['check'] = 'success';
            $response['message'] = 'User successfully registered';
            $err_code = 201;
        }else{
            $response['message'] = 'Registration Failed';
            $err_code = 500;
        }

        return response()->json($response, $err_code);
    }


    public function login(Request $request)
    {
        // Validation
        $credentials = $request->validate([
            'email' => 'required|string|email',
            // 'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check credentials
        if (!Auth::attempt($credentials)) {
            $response['message'] = 'Invalid credentials';
            // $response['error'] = $credentials->errors();
            $err_code = 401;
            return response()->json($response, $err_code);
        }

        // Authenticate and generate token
        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;

        // Return success response with token
        if($user){
            $response['check'] = 'success';
            $response['message'] = 'Login successful';
            $response['user'] = $user;
            $response['token'] = $token;
            $err_code = 200;
        }else{
            $response['message'] = 'Login Failed';
            $err_code = 500;
        }
        return response()->json($response, 200);
    }
    public function logout(Request $request)
    {
        $res = $request->user()->currentAccessToken()->delete();
        if($res){
            $response['check'] = 'success';
            $response['message'] = 'Logout successful';
            $err_code = 200;
        }else{
            $response['message'] = 'Logout Failed';
            $err_code = 500;
        }
        return response()->json($response, $err_code);
    }

}
