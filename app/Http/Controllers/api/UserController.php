<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function add_address(Request $request,$id = null){
        // ini_set("display_errors",1);
        $response = array('check'=> 'failed' , 'message'=> 'Something Error');
        try {

            // print_r($request->all());
            $validatedData = $request->validate([
                'user_id' => 'required|numeric',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'pin' => 'required|numeric|digits:6',
                'address' => 'required|string|max:255',
            ]);
                // $errors = $request->errors();
            if($id){
                $data = UserAddress::find($id);
                $data->user_id = $validatedData['user_id'];
                $data->city = $validatedData['city'];
                $data->state = $validatedData['state'];
                $data->pin = $validatedData['pin'];
                $data->address = $validatedData['address'];

                if($data->save()){
                    $response['check'] = 'success';
                    $response['message'] = 'Data updated Successfully';
                    $response['data'] = $request->all();
                }else{
                    $response['message'] = 'Something error, please try again';
                }
            }else{
                $data = new UserAddress;
                $data->user_id = $validatedData['user_id'];
                $data->city = $validatedData['city'];
                $data->state = $validatedData['state'];
                $data->pin = $validatedData['pin'];
                $data->address = $validatedData['address'];

                if($data->save()){
                    $response['check'] = 'success';
                    $response['message'] = 'Data inserted Successfully';
                    $response['data'] = $request->all();
                }else{
                    $response['message'] = 'Something error, please try again';
                }
            }
            return response()->json($response);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $errorMessages = null;
            foreach ($errors as $field => $messages) {
                if($messages[0]){
                    $errorMessages = $messages[0];
                    continue;
                }
            }
            $response['message'] =  $errorMessages;
            return response()->json($response);
        }
    }
    public function get_user_address($id){
        $response = array('check'=> 'failed' , 'message'=> 'Something Error');
        $users = UserAddress::where(['id'=> $id])->get();

        if($users){
            $user_data = User::where(['id'=> $users[0]['id']])->get();
            $temp = array();
            $temp['name'] = $user_data[0]['name'];
            $temp['email'] = $user_data[0]['email'];
            $temp['mobile'] = $user_data[0]['mobile'];
            $temp['city'] = $users[0]['city'];
            $temp['state'] = $users[0]['state'];
            $temp['pin'] = $users[0]['pin'];
            $temp['address'] = $users[0]['address'];

            $response['check'] = 'success';
            $response['message'] = 'fetch sucessfully';
            $response['data'] =$temp;
        }
        return response()->json($response);
    }
    public function delete_shipping_address($id){
        $response = array('check'=> 'failed' , 'message'=> 'Not Found');
        $users = UserAddress::where(['id'=> $id])->delete();
        if($users){
            $response['check'] = 'success';
            $response['message'] = 'Delete sucessfully';
        }
        return response()->json($response);
    }

}
