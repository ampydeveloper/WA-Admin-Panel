<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;

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
                    'driver_first_name' => 'required|string',
                    'driver_last_name' => 'required|string',
                    'driver_email' => 'required|string|email|unique:users,email' . $request->user()->id,
                    'driver_phone' => 'required',
                    'driver_address' => 'required',
                    'driver_city' => 'required',
                    'driver_province' => 'required',
                    'driver_zipcode' => 'required',
                    'driver_is_active' => 'required',
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
            $driverDetails = User::whereId($request->user()->id)->first();
            if ($request->password != '' && $request->password != null) {
                $driverDetails->password = bcrypt($request->password);
            }
            $driverDetails->prefix = (isset($request->driver_prefix) && $request->driver_prefix != '' && $request->driver_prefix != null) ? $request->driver_prefix : null;
            $driverDetails->first_name = $request->driver_first_name;
            $driverDetails->last_name = $request->driver_last_name;
            $driverDetails->email = $request->driver_email;
            $driverDetails->phone = $request->driver_phone;
            $driverDetails->address = $request->driver_address;
            $driverDetails->city = $request->driver_city;
            $driverDetails->state = $request->driver_province;
            $driverDetails->zip_code = $request->driver_zipcode;
            $driverDetails->user_image = (isset($request->driver_image) && $request->driver_image != '' && $request->driver_image != null) ? $request->driver_image : null;
            $driverDetails->is_active = $request->driver_is_active;
            if ($driverDetails->save()) {
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
