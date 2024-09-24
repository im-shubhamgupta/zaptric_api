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
    protected $response = array('check'=> 'failed', 'message' => 'something Error');
    public function register(Request $request)
    {
        //,'err_code'=>500
        // print_r($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|numeric|digits:10|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            $this->response['message'] = 'validation error';
            $this->response['error'] = $validator->errors();
            $err_code = 422;
            return response()->json($this->response, $err_code);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);
        if($user){
            $this->response['check'] = 'success';
            $this->response['message'] = 'User successfully registered';
            $err_code = 201;
        }else{
            $this->response['message'] = 'Registration Failed';
            $err_code = 500;
        }

        return response()->json($this->response, $err_code);
    }


    public function login(Request $request)
    {
        $key = isset($request['mobile']) ? 'mobile' : 'email';

        $credentials = $request->validate([
            $key => 'required|string',
            'password' => 'required|string',
        ]);
        // // Check credentials
        if (!Auth::attempt($credentials)) {
            $this->response['message'] = 'Invalid credentials';
            // $response['error'] = $credentials->errors();
            $err_code = 401;
            return response()->json($this->response, $err_code);
        }
        // Authenticate and generate token
        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;

        // Return success response with token
        if($user){
            $this->response['check'] = 'success';
            $this->response['message'] = 'Login successful';
            $this->response['user'] = $user;
            $this->response['token'] = $token;
            $err_code = 200;
        }else{
            $this->response['message'] = 'Login Failed';
            $err_code = 500;
        }
        return response()->json($this->response, 200);
    }
    public function logout(Request $request)
    {
        $res = $request->user()->currentAccessToken()->delete();
        if($res){
            $this->response['check'] = 'success';
            $this->response['message'] = 'Logout successful';
            $err_code = 200;
        }else{
            $this->response['message'] = 'Logout Failed';
            $err_code = 500;
        }
        return response()->json($this->response, $err_code);
    }

}
