<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;
use App\User;

class CompanyController extends Controller
{
     /**
     * create manager
     */
    public function createHauler(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string',
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
            DB::beginTransaction();
            if($request->user_image != '' && $request->user_image != null) {
                $file= $request->user_image;
            } else {
                $file = '';
            }

            //random string for new password
            $newPassword = Str::random();

            //create new user
            $user = new User([
                'prefix' => $request->prefix,
                'first_name' => $request->customer_name,
                'email' => $request->email,
                'role_id' => $request->customer_role,
                'phone' => $request->phone,
                'user_image' => $file,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->province,
                'zip_code' => $request->zipcode,
                'user_image' => $file,
                'is_confirmed' => 1,
		'is_active' => $request->is_active,
                'password' => bcrypt($newPassword)
            ]);
           if($user->save() && $request->role_id != config('constant.roles.Company')) {
            //send email for new email and password
            $this->_confirmPassword($user, $newPassword);
           }
            DB::commit();
            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Successfully created Hauler!',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
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
    * list of Hauler
    * return array()
    */
    public function listHauler(){
      return response()->json([
            'status' => true,
            'message' => 'Hauler List',
            'data' => User::whereRoleId(config('constant.roles.Company'))->orderBy("created_at", 'DESC')->get()
        ], 200);
    }

    /**
    * get Hauler by id
    * return array()
    */
    public function getHauler(Request $request){
     return response()->json([
            'status' => true,
            'message' => 'Manager Details',
            'data' => user::whereId($request->customer_id)->first()
        ], 200);
    }

    /**
     * update manager
     */
    public function updateHauler(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,'.$request->customer_id,
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
            DB::beginTransaction();
            //gert hauler details
            $haulerDetails = User::whereId($request->customer_id)->first();
            //if password request submitted
            if($request->password != '' && $request->password != null) {
                $haulerDetails->password = bcrypt($request->password);
            }

            //create new user
            $haulerDetails->first_name = $request->customer_name;
            $haulerDetails->prefix = $request->prefix;
            $haulerDetails->email = $request->email;
            $haulerDetails->phone = $request->phone;
            $haulerDetails->address = $request->address;
            $haulerDetails->city = $request->city;
            $haulerDetails->state = $request->province;
            $haulerDetails->zip_code = $request->zipcode;
            $haulerDetails->user_image = $request->user_image;
            
            $haulerDetails->save();
            //check if not admin
         
            DB::commit();
            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Hauler Customer details updated Successfully!',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
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
     * delete hauler
     */
    public function deleteHauler(Request $request)
    {
        try {
            User::whereId($request->customer_id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Hauler customer deleted Successfully',
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
