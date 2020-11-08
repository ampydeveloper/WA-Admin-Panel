<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;

use Storage;
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

class DriverController extends Controller {
    public function dashboard(Request $request) {
        dd('dashboard');
    }
    
    public function editProfile(Request $request) {
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|string|email|unique:users,email' . $request->user()->id,
                    'image' => 'sometimes|required',
                    'phone' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'province' => 'required',
                    'zipcode' => 'required',
                    'is_active' => 'required',
                    'licence_no' => 'required|unique:drivers,driver_licence' . $request->user()->id,
                    'expiry_date' => 'required',
                    'licence_image' => 'sometimes|required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $driver = $request->user();
        
        if($driver->role_id != config('constant.roles.Driver')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized access.',
                'data' => []
            ], 421);
        }
        dd($request->all());
        try {
            DB::beginTransaction();
            if ($request->password != '' && $request->password != null) {
                $driver->password = bcrypt($request->password);
            }
            $driver->prefix = (isset($request->driver_prefix) && $request->driver_prefix != '' && $request->driver_prefix != null) ? $request->driver_prefix : null;
            $driver->first_name = $request->driver_first_name;
            $driver->last_name = $request->driver_last_name;
            $driver->email = $request->driver_email;
            $driver->phone = $request->driver_phone;
            $driver->address = $request->driver_address;
            $driver->city = $request->driver_city;
            $driver->state = $request->driver_province;
            $driver->zip_code = $request->driver_zipcode;
            $driver->is_active = $request->driver_is_active;
            
            if (is_file($request->image)) {
                $imageName = rand() . time() . '.' . $request->image->extension();
                (Storage::disk('user_images')->put($driver->id . '/' . $imageName, file_get_contents($request->image))) ? $imageName : false;
                $driver->user_image = $imageName;
            }
            $driverDetails = Driver::where('user_id', $driver->id)->first();
            $driverDetails->expiry_date = $request->expiry_date;
            $driverDetails->driver_licence = $request->licence_no;
            if (is_file($request->licence_image)) {
                $imageName = rand() . time() . '.' . $request->licence_image->extension();
                (Storage::disk('user_images')->put($driver->id . '/' . $imageName, file_get_contents($request->licence_image))) ? $imageName : false;
                $driverDetails->document = $imageName;
            }
            if ($driver->save() && $driverDetails->save()) {
                DB::commit();
                return response()->json([
                            'status' => true,
                            'message' => 'Driver updated successfully.',
                            'data' => []
                                ], 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
    
    public function earnings(Request $request) {
            dd('earnings');
    }
    
    public function routes(Request $request) {
        dd('routes');
    }
    
    public function startRoute(Request $request) {
        dd('routes');
    }
    public function startJob(Request $request) {
        dd('startJob');
    }
    public function endRoute(Request $request) {
        dd('endRoute');
    }
    public function endJob(Request $request) {
        dd('endJob');
    }
    public function jobFilter(Request $request) {
        dd('jobFilter');
    }

}
