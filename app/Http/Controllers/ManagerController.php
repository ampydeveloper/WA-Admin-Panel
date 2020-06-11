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
use App\ManagerDetail;

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
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'phone' => $request->phone,
                'user_image' => $file,
                'phone' => $request->manager_phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'zip_code' => $request->manager_zipcode,
                'user_image' => $file,
                'is_confirmed' => 1,
		        'is_active' => 1,
                'password' => bcrypt($newPassword)
            ]);
            if($user->save() && $request->role_id != config('constant.roles.Admin')) {
                $managerDetails = new ManagerDetail([
                    'user_id' => $user->id,
                    'salary' => $request->salary,
                    'identification_number' => $request->identification_number,
                    'joining_date' => $request->joining_date,
                    'releaving_date' => $request->releaving_date,
                    'document' => $request->document
                ]);

                $managerDetails->save();
            }
            //send email for new email and password
            $this->_confirmPassword($user, $newPassword);
            DB::commit();
            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Successfully created Manager!',
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
     * update manager
     */
    public function updateManager(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
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
            DB::beginTransaction();
            //gert manager details
            $managerDetails = User::whereId($request->manager_id)->first();
            //if password request submitted
            if($request->password != '' && $request->password != null) {
                $managerDetails->password = bcrypt($request->password);
            }

            //create new user
            $managerDetails->first_name = $request->first_name;
            $managerDetails->last_name = $request->last_name;
            $managerDetails->email = $request->email;
            $managerDetails->phone = $request->manager_phone;
            $managerDetails->address = $request->address;
            $managerDetails->city = $request->city;
            $managerDetails->state = $request->state;
            $managerDetails->country = $request->country;
            $managerDetails->zip_code = $request->manager_zipcode;
            $managerDetails->user_image = $request->user_image;
            
            $managerDetails->save();
            //check if not admin
            if($managerDetails->role_id != config('constant.roles.Admin')) {
                ManagerDetail::whereUserId($request->manager_id)->update([
                    'salary' => $request->salary,
                        'identification_number' => $request->identification_number,
                        'joining_date' => $request->joining_date,
                        'releaving_date' => $request->releaving_date,
                        'document' => $request->document
                ]);
            }
            DB::commit();
            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Manager details updated Successfully!',
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
     * get manager details
     */
    public function getManager(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Manager Details',
            'data' => ManagerDetail::whereUserId($request->manager_id)->with('user')->first()
        ], 200);

    }

    /**
     * get admin details
     */
    public function getAdmin(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Admin Details',
            'data' => User::whereId($request->admin_id)->first()
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
                'message' => 'User deleted Successfully',
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
            'data' => User::whereRoleId(config('constant.roles.Admin_Manager'))->with('manager')->orderBy("created_at", 'DESC')->get()
        ], 200);

    }

    /**
     * list admin
     */
    public function listAdmin()
    {
        return response()->json([
            'status' => true,
            'message' => 'Admin List',
            'data' => User::whereRoleId(config('constant.roles.Admin'))->orderBy("created_at", 'DESC')->get()
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
