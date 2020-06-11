<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mail;
use App\Service;
use App\TimeSlots;
use App\ServicesTimeSlot;
use App\User;
use App\ManagerDetail;
use App\CustomerFarm;

class CustomerController extends Controller
{
    /**
     * create customer
     */
    public function createCustomer(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'prefix' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }

        try {

            //use of db transactions
            DB::beginTransaction();

            //random string for new password
            $newPassword = Str::random();

            //create new user
            $user = new User([
                'first_name' => $request->customer_name,
                'prefix' => $request->prefix,
                'email' => $request->email,
                'role_id' => $request->customer_role,
                'phone' => $request->phone,
                'user_image' => $request->user_image,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->province,
                'zip_code' => $request->zipcode,
                'is_active' => $request->is_active,
                'is_confirmed' => 1,
                'password' => bcrypt($newPassword)
            ]);
            if($user->save()) {
                //send email for new email and password
                $this->_confirmPassword($user, $newPassword);
                //random string for new password
                $newPassword = Str::random();

                $saveManger = new User([
                    'first_name' => $request->manager_name,
                    'prefix' => $request->manager_prefix,
                    'created_by' => $user->id,
                    'email' => $request->manager_email,
                    'role_id' => $request->manager_role,
                    'phone' => $request->manager_phone,
                    'user_image' => $request->manager_image,
                    'address' => $request->manager_address,
                    'city' => $request->manager_city,
                    'state' => $request->manager_province,
                    'zip_code' => $request->manager_zipcode,
                    'is_active' => 1,
                    'is_confirmed' => 1,
                    'password' => bcrypt($newPassword)
                ]);

                if($saveManger->save()) {
                    $mangerDetails = new ManagerDetail([
                        'user_id' => $saveManger->id,
                        'identification_number' => $request->manager_id_card,
                        'document' => $request->manager_card_image
                    ]);

                    $mangerDetails->save();

                    //save customer farm details
                    $farmDetails = new CustomerFarm([
                        'customer_id' => $user->id,
                        'manager_id' => $saveManger->id,
                        'farm_address' => $request->farm_address,
                        'farm_city' => $request->farm_city,
                        'farm_image' => json_encode($request->farm_images),
                        'farm_province' => $request->farm_province,
                        'farm_unit' => $request->farm_unit,
                        'farm_zipcode' => $request->farm_zipcode,
                        'farm_active' => $request->farm_active,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude
                    ]);

                    $farmDetails->save();
                }
            }
            //send email for new email and password
            $this->_confirmPassword($saveManger, $newPassword);
            DB::commit();

            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Customer created successfully.',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            //rollback transactions
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
     * edit service
     */
    public function editService(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string',
            'price' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }

        try {
            //update service
            Service::whereId($request->service_id)->update([
                'service_name' => $request->service_name,
                'price' => $request->price,
                'description' => $request->description,
                'service_rate' => $request->service_rate,
                'slot_type' => $request->slot_type,
                'slot_time' => json_encode($request->slot_time),
                'service_image' => $request->service_image
            ]);

            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Service edit successfully.',
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
     * edit service time slot
     */
    public function editTimeSlot(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'slot_type' => 'required|string',
            'slot_start' => 'required',
            'slot_end' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }

        try {
            //create new user
            ServicesTimeSlot::whereId($request->time_slot_id)->update([
                'slot_type' => $request->slot_type,
                'slot_start' => $request->slot_start,
                'slot_end' => $request->slot_end
            ]);

            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Service time slot updated successfully.',
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
     * list customer
     */
    public function listCustomer()
    {
        $getCustomer = User::with(['customerManager' => function ($query) {
            $query->with("manager", "farms");
        }])
        ->whereRoleId(config('constant.roles.Customer'))->get();

        return response()->json([
            'status' => true,
            'message' => 'Service Listing.',
            'data' => $getCustomer
        ], 200);
    }

    /**
     * get service
     */
    public function getService(Request $request)
    {
        $fetchService = Service::whereId($request->service_id)->first();
        //check if exist
        if ($fetchService != null) {
            $status = true;
            $message = "Service Found.";
            $statusCode = 200;
        } else {
            $status = false;
            $message = "Service not found.";
            $statusCode = 400;
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $fetchService
        ], $statusCode);
    }

    /**
     * delete service
     */
    public function deleteService(Request $request)
    {
        //check if exist
        if (Service::whereId($request->service_id)->delete()) {
            $status = true;
            $message = "Service deleted successfully.";
            $statusCode = 200;
        } else {
            $status = false;
            $message = "Service not found.";
            $statusCode = 400;
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => []
        ], $statusCode);
    }

    /**
     * get time slots
     * @param time slots type(morning, afternoon)
     * return array()
     */
    public function getTimeSlots(Request $request)
    {

        $getTime = TimeSlots::where('slot_type', $request->slot_type)->get();
        if ($getTime->count()) {
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $getTime
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'no time slots found',
                'data' => $getTime
            ], 200);
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
