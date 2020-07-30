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
use App\CustomerCardDetail;
use App\Payment;
use App\Job;
use Carbon\Carbon;

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
            if ($user->save()) {
                //send email for new email and password
                $this->_confirmPassword($user, $newPassword);
                //save customer farm details
                $farmDetails = new CustomerFarm([
                    'customer_id' => $user->id,
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

                $managerIds = array();
                foreach ($request->manager_details as $manager) {
                    //random string for new password
                    $newPassword = Str::random();

                    $saveManger = new User([
                        'first_name' => $manager['manager_name'],
                        'prefix' => $manager['manager_prefix'],
                        'created_by' => $user->id,
                        'farm_id' => $farmDetails->id,
                        'email' => $manager['manager_email'],
                        'role_id' => $manager['manager_role'],
                        'phone' => $manager['manager_phone'],
                        'user_image' => $manager['manager_image'],
                        'address' => $manager['manager_address'],
                        'city' => $manager['manager_city'],
                        'state' => $manager['manager_province'],
                        'zip_code' => $manager['manager_zipcode'],
                        'is_active' => 1,
                        'is_confirmed' => 1,
                        'password' => bcrypt($newPassword)
                    ]);

                    if ($saveManger->save()) {
                        $managerIds[] = $saveManger->id;
                        $mangerDetails = new ManagerDetail([
                            'user_id' => $saveManger->id,
                            'identification_number' => $manager['manager_id_card'],
                            'document' => $manager['manager_card_image']
                        ]);
                        //save manager details
                        $mangerDetails->save();

                        //send email for new email and password
                        $this->_confirmPassword($saveManger, $newPassword);
                    }
                }
            }
            DB::commit();

            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Customer created successfully.',
                'data' => $user
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
     * create customer farm
     */
    public function createFarm(Request $request)
    {

	//validate request
	$validator = Validator::make($request->all(), [
	    'manager_details.*.email' => 'required|string|email|unique:users',
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
            $farmDetails = new CustomerFarm([
            'customer_id' => $request->customer_id,
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
        $managerIds = array();
        foreach ($request->manager_details as $manager) {
            //random string for new password
            $newPassword = Str::random();

            $saveManger = new User([
                'first_name' => $manager['manager_name'],
                'prefix' => $manager['manager_prefix'],
                'created_by' => $request->customer_id,
                'farm_id' => $farmDetails->id,
                'email' => $manager['email'],
                'role_id' => $manager['manager_role'],
                'phone' => $manager['manager_phone'],
                'user_image' => $manager['manager_image'],
                'address' => $manager['manager_address'],
                'city' => $manager['manager_city'],
                'state' => $manager['manager_province'],
                'zip_code' => $manager['manager_zipcode'],
                'is_active' => 1,
                'is_confirmed' => 1,
                'password' => bcrypt($newPassword)
            ]);

            if ($saveManger->save()) {
                $managerIds[] = $saveManger->id;
                $mangerDetails = new ManagerDetail([
                    'user_id' => $saveManger->id,
                    'identification_number' => $manager['manager_id_card'],
                    'document' => $manager['manager_card_image']
                ]);
                //save manager details
                $mangerDetails->save();

                //send email for new email and password
                $this->_confirmPassword($saveManger, $newPassword);
            }
        }
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
     * create customer
     */
    public function createHauler(Request $request)
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
            if ($user->save()) {
                //send email for new email and password
                $this->_confirmPassword($user, $newPassword);
            }
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
     * create customer manager
     */
    public function createCustomerManager(Request $request)
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

            $saveManger = new User([
                'first_name' => $request->manager_name,
                'prefix' => $request->manager_prefix,
                'created_by' => 1234,
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

            if ($saveManger->save()) {
                $mangerDetails = new ManagerDetail([
                    'user_id' => $saveManger->id,
                    'identification_number' => $request->manager_id_card,
                    'document' => $request->manager_card_image
                ]);
            }

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
     * create customer farm
     */
    public function createCustomerFarm(Request $request)
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

            //save customer farm details
            $farmDetails = new CustomerFarm([
                'customer_id' => 1234,
                'manager_id' => 1234,
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
     * list customer
     */
    public function listCustomer()
    {
        // \DB::connection()->enableQueryLog();

        $getCustomer = User::with('farmlist.farmManager')
            ->whereRoleId(config('constant.roles.Customer'))->get();
        // $queries = \DB::getQueryLog();
        // dd($queries);
        return response()->json([
            'status' => true,
            'message' => 'Service Listing.',
            'data' => $getCustomer
        ], 200);
    }

    /**
     * list hauler
     */
    public function listHauler()
    {
        $getCustomer = User::with(['customerManager' => function ($query) {
            $query->with("manager", "farms");
        }])
            ->whereRoleId(config('constant.roles.Company'))->get();

        return response()->json([
            'status' => true,
            'message' => 'Service Listing.',
            'data' => $getCustomer
        ], 200);
    }

    /**
     * get customer details
     * @param id
     * return customer array
     */
    public function getCustomer(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Customer Details',
            'data' => User::whereId($request->customer_id)->with(['customerManager' => function ($query) {
                $query->with("manager", "farms");
            }])->first()
        ], 200);
    }

    /**
     * get farm and manager details
     * @param id
     * return farm and manager array
     */
    public function getFarmAndManager(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Customer Details',
            'data' => CustomerFarm::with("farmManager.manager")->whereCustomerId($request->customer_id)->get()
        ], 200);
    }
    /**
     * update customer
     */
    public function updateCustomer(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,' . $request->customer_id,
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
            //gert customer details
            $customerDetails = User::whereId($request->customer_id)->first();
            //create new user
            $customerDetails->prefix = $request->prefix;
            $customerDetails->first_name = $request->customer_name;
            $customerDetails->email = $request->email;
            $customerDetails->phone = $request->phone;
            $customerDetails->address = $request->address;
            $customerDetails->city = $request->city;
            $customerDetails->state = $request->province;
            $customerDetails->zip_code = $request->zipcode;
            $customerDetails->user_image = $request->user_image;
            $customerDetails->is_active = $request->is_active;
            if ($customerDetails->save()) {

                DB::commit();

                //return success response
                return response()->json([
                    'status' => true,
                    'message' => 'Customer updated successfully.',
                    'data' => []
                ], 200);
            }
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

    /*
   * get card listing based on customer id
   */
    public function getAllCard(Request $request)
    {

        $userList = User::where('created_by', $request->customer_id)->get();
        $userId = array();
        array_push($userId, trim($request->customer_id, '"'));
        if ($userList->count()) {
            foreach ($userList as $user) {
                array_push($userId, $user->id);
            }
        }
        $card = CustomerCardDetail::whereIn('customer_id', $userId)->get();
        //return success response
        return response()->json([
            'status' => true,
            'message' => 'Card List successfully.',
            'data' => $card
        ], 200);
    }

    /*
   * get customer records by customer id
   */
    public function getAllRecords(Request $request)
    {
        $records = array();
        $userList = User::where('created_by', $request->customer_id)->get();
        $userId = array();
        $managerId = array();
        array_push($userId, trim($request->customer_id, '"'));
        if ($userList->count()) {
            foreach ($userList as $user) {
                array_push($userId, $user->id);
            }
        }

        $farmrecord = CustomerFarm::where('customer_id', $request->customer_id)->count();
        $allamount = Payment::whereIn('user_id', $userId)->sum('amount');
        $monthamount = Payment::whereIn('user_id', $userId)->whereMonth(
            'created_at',
            '=',
            Carbon::now()->subMonth(12)
        )->sum('amount');
        $totaljobs = Job::where('customer_id', $request->customer_id)->count();
        $memebersince = User::whereId($request->customer_id)->pluck('created_at');
        $jobs = Job::where('customer_id', $request->customer_id)->with(['farm', 'truck_driver', 'skidsteer_driver', 'service'])->get();
        $records = array(
            'monthamount' => $monthamount,
            'allamount' => $allamount,
            'farmrecord' => $farmrecord,
            'totaljobs' => $totaljobs,
            'memebersince' => $memebersince[0],
            'jobs' => $jobs
        );
        //return success response
        return response()->json([
            'status' => true,
            'message' => 'Card List successfully.',
            'data' => $records
        ], 200);
    }

    /*
   * update farm and customer details
   */
    public function updateFarmManager(Request $request)
    {
        try {
            //validate request
            // $validator = Validator::make($request->all(), [
            //     'manager_name' => 'required|string',
            //     'manager_email' => 'required|string|email|unique:users,email,' . $request->manager_id,
            //     'manager_prefix' => 'required'
            // ]);

            foreach($request->managers as $manager) {
                //update manager details
                $updateUser = User::whereId($manager['manager_id'])->update([
                    'first_name' => $manager['manager_name'],
                    'prefix' => $manager['manager_prefix'],
                    'email' => $manager['manager_email'],
                    'phone' => $manager['manager_phone'],
                    'user_image' => $manager['manager_image'],
                    'address' => $manager['manager_address'],
                    'city' => $manager['manager_city'],
                    'state' => $manager['manager_province'],
                    'zip_code' => $manager['manager_zipcode']
                ]);

                $mangerDetails = ManagerDetail::whereUserId($manager['manager_id'])->update([
                    'identification_number' => $manager['manager_id_card'],
                    'document' => $manager['manager_card_image']
                ]);
            }

            //save customer farm details
            $farmDetails = CustomerFarm::whereId($request->farm['farm_id'])->update([
                'farm_address' => $request->farm['farm_address'],
                'farm_city' => $request->farm['farm_city'],
                'farm_image' => json_encode($request->farm['farm_images']),
                'farm_province' => $request->farm['farm_province'],
                'farm_unit' => $request->farm['farm_unit'],
                'farm_zipcode' => $request->farm['farm_zipcode'],
                'farm_active' => $request->farm['farm_active'],
                'latitude' => $request->farm['latitude'],
                'longitude' => $request->farm['longitude']
            ]);

            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Manager and Farm details updated successfully.',
                'data' => $request->all()
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
  * get customer farm
  */
  public function getFarm(Request $request){
    $farms = CustomerFarm::where('customer_id', $request->customer_id)->get();
    if($farms->count()){
	    return response()->json([
		'status' => true,
		'message' => 'Manager and Farm details updated successfully.',
		'data' => $farms
	    ], 200);
    }else{
      	    return response()->json([
		'status' => false,
		'message' => 'No farm found.',
		'data' => []
	    ], 200);
    }
  }
  
  public function getFarmManager(Request $request){
    $farms = User::where('farm_id', $request->farm_id)->get();
    if($farms->count()){
	    return response()->json([
		'status' => true,
		'message' => 'Manager and Farm details updated successfully.',
		'data' => $farms
	    ], 200);
    }else{
      	    return response()->json([
		'status' => false,
		'message' => 'No farm manager found.',
		'data' => []
	    ], 200);
    }
  }
 
}
