<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Service;
use App\TimeSlots;
use Illuminate\Support\Str;

class ServicesController extends Controller
{
    /**
     * create service
     */
    public function createService(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string',
            'price' => 'required',
            'description' => 'required',
            'slot_type' => 'required',
            'slot_time' => 'required',
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
            // DB::beginTransaction();

            //create new user
            $service = new Service([
                'service_name' => $request->service_name,
                'price' => $request->price,
                'description' => $request->description,
                'service_image' => $request->service_image,
                'service_rate' => $request->service_rate,
                'slot_type' => json_encode($request->slot_type),
                'slot_time' => json_encode($request->slot_time),
            ]);
            //save service
            $service->save();
         
            // DB::commit();

            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Service created successfully.',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            //rollback transactions
            // DB::rollBack();
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
                'slot_type' => json_encode($request->slot_type),
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
     * list services
     */
    public function listServices() {
        $getAllServices = Service::get();

        foreach($getAllServices as $key => $service) {
            //get timeSlots
            $timeSlots = TimeSlots::whereIn('id', json_decode($service->slot_time))->get();
            //set timeSlots
            $getAllServices[$key]["timeSlots"] = $timeSlots;
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Service Listing.',
            'data' => $getAllServices
        ], 200);
    }

    /**
     * get service
     */
    public function getService(Request $request) {
        $fetchService = Service::whereId($request->service_id)->first();
        //check if exist
        if($fetchService != null) {
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
    public function deleteService(Request $request) {
        //check if exist
        if(Service::whereId($request->service_id)->delete()) {
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
    public function getTimeSlots(Request $request){

	     $getTime = TimeSlots::whereSlotType($request->slot_type)->get();
	     if($getTime->count()){
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $getTime
            ], 200);  
	     }else {
            return response()->json([
                'status' => false,
                'message' => 'no time slots found',
                'data' => $getTime
            ], 200);
	     }
	     
    }
  
}
