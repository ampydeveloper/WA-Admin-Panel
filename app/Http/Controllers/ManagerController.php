<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mail;
use Carbon\Carbon;
use Validator;
use App\User;

class ManagerController extends Controller
{
    /**
     * create manager
     */
    public function createManager(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }

        try {
            if($request->user_image != '' && $request->user_image != null) {
//                //upload path
//                $folderPath = "images/";
//                //get base64 image
//                $img = $request->user_image;
//                //decode base64
//                $image_parts = explode(";base64,", $img);
//                $image_type_aux = explode("image/", $image_parts[0]);
//                $image_type = $image_type_aux[1];
//                $image_base64 = base64_decode($image_parts[1]);
//                $file = $folderPath . uniqid() . '. '.$image_type;
//                
//                //check if directory exist if not create one
//                $path = public_path().'/images';
//                if (!file_exists($path)) {
//                    mkdir($path, 0777, true);
//                }
//                //upload image
//                file_put_contents($file, $image_base64);
                $file= $request->user_image;
            } else {
                $file = '';
            }

            //random string for new password
            $newPassword = Str::random();

            //create new user
            $user = new User([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'role_id' => config('constant.roles.Admin_Manager'),
                'phone' => $request->phone,
                'user_image' => $file,
                'is_confirmed' => 1,
		'is_active' => 1,
                'password' => bcrypt($newPassword)
            ]);
            if($user->save()) {
                //send email for new email and password
                $this->_confirmPassword($user, $newPassword);
            }
            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Successfully created Manager!',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            //make log of errors
            Log::error(json_encode($e->getMessage()));
            //return with error
            return response()->json([
                'status' => false,
                'message' => 'Internal server error!',
                'data' => []
            ], 500);
        }
    }

    /**
     * update manager
     */
    public function updateManager(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,'.$request->manager_id,
            'password' => 'confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }

        try {
            //gert manager details
            $managerDetails = User::whereId($request->manager_id)->first();
            
            //if admin changes profile image
            if($request->user_image != null && $request->user_image != '') {
                //upload path
//                $folderPath = "images/";
//                //get base64 image
//                $img = $request->user_image;
//                //decode base64
//                $image_parts = explode(";base64,", $img);
//                $image_type_aux = explode("image/", $image_parts[0]);
//                $image_type = $image_type_aux[1];
//                $image_base64 = base64_decode($image_parts[1]);
//                $file = $folderPath . uniqid() . '. '.$image_type;
//                
//                //check if directory exist if not create one
//                $path = public_path().'/images';
//                if (!file_exists($path)) {
//                    mkdir($path, 0777, true);
//                }
//                //upload image
//                file_put_contents($file, $image_base64);

                $managerDetails->user_image = $request->user_image;
            }
            //if password request submitted
            if($request->password != '' && $request->password != null) {
                $managerDetails->password = bcrypt($request->password);
            }

            //create new user
            $managerDetails->first_name = $request->first_name;
            $managerDetails->last_name = $request->last_name;
            $managerDetails->email = $request->email;
            $managerDetails->phone = $request->phone;
            
            $managerDetails->save();
            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Manager details updated Successfully!',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            //make log of errors
            Log::error(json_encode($e->getMessage()));
            //return with error
            return response()->json([
                'status' => false,
                'message' => 'Internal server error!',
                'data' => []
            ], 500);
        }
    }

    /**
     * get manager details
     */
    public function getManager(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Manager Details',
            'data' => User::whereId($request->manager_id)->first()
        ], 200);

    }

    /**
     * delete manager
     */
    public function deleteManager(Request $request)
    {
        try {
            User::whereId($request->manager_id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Manager deleted Successfully',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            //make log of errors
            Log::error(json_encode($e->getMessage()));
            //return with error
            return response()->json([
                'status' => false,
                'message' => 'Internal server error!',
                'data' => []
            ], 500);
        }

    }

    /**
     * list manager
     */
    public function listManager()
    {
        return response()->json([
            'status' => true,
            'message' => 'Manager List',
            'data' => User::whereRoleId(config('constant.roles.Admin_Manager'))->get()
        ], 200);

    }

    /**
     * email for new registration and password
     */
    public function _confirmPassword($user, $newPassword)
    {
        $name = $user->first_name . ' ' . $user->last_name;
        $data = array(
            'user' => $user,
            'password' => $newPassword
        );

        Mail::send('email_templates.welcome_email_manager', $data, function ($message) use ($user, $name) {
            $message->to($user->email, $name)->subject('Login Details');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });
    }
}
