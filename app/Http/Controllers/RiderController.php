<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\rider;
use Illuminate\Validation\ValidationException;

class RiderController extends Controller
{
    public function add_rider(Request $request,$id = null){
        ini_set("display_errors",1);
        $response = array('check'=> 'failed' , 'message'=> 'Something Error');
        try {

            // print_r($request->all());
            $rider_id = ($id) ? ','.$id : '';
            $validatedData = $request->validate([
                'name' => 'required|string|max:100',
                'mobile' => 'required|numeric|digits:10|unique:rider,mobile'.$rider_id ,
                'current_location' => 'required|string|max:50',
                'from' => 'required|string|max:50',
                'to' => 'required|string|max:50',
            ],[
                'mobile.unique' => 'The mobile number has already been taken.',
            ]);
                // $errors = $request->errors();
            if($id){
                $data = rider::find($id);
                $data->name = $validatedData['name'];
                $data->mobile = $validatedData['mobile'];
                $data->current_location = $validatedData['current_location'];
                $data->from = $validatedData['from'];
                $data->to = $validatedData['to'];

                if($data->save()){
                    $response['check'] = 'success';
                    $response['message'] = 'Data updated Successfully';
                    $response['data'] = $request->all();
                }else{
                    $response['message'] = 'Something error, please try again';
                }
            }else{

                $data = new Rider;
                $data->name = $validatedData['name'];
                $data->mobile = $validatedData['mobile'];
                $data->current_location = $validatedData['current_location'];
                $data->from = $validatedData['from'];
                $data->to = $validatedData['to'];

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
    public function view_rider(){
        $response = array('check'=> 'failed' , 'message'=> 'Something Error');
        $riders = rider::all();
        if($riders){
            $response['check'] = 'success';
            $response['message'] = 'fetch sucessfully';
            $response['data'] = $riders;
        }
        return response()->json($response);
    }

    public function get_rider($id){
        $response = array('check'=> 'failed' , 'message'=> 'Something Error');
        $riders = rider::where(['id'=> $id])->get();
        if($riders){
            $response['check'] = 'success';
            $response['message'] = 'fetch sucessfully';
            $response['data'] = $riders;
        }
        return response()->json($response);
    }
    public function delete_rider($id){
        $response = array('check'=> 'failed' , 'message'=> 'Not Found');
        $riders = rider::where(['id'=> $id])->delete();
        if($riders){
            $response['check'] = 'success';
            $response['message'] = 'Delete sucessfully';
        }
        return response()->json($response);
    }

}
