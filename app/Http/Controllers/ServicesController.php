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
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'service_image' => 'required|string',
            'service_for' => 'required|numeric',
            'slot_type' => 'required_if:service_for,==,4|array',
            'service_type' => 'required_if:service_for,==,4',
            'time_taken_to_complete_service' => 'required_if:service_for,==,4',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }
        try {
            $service = new Service([
                'service_name' => $request->service_name,
                'price' => $request->price,
                'description' => $request->description,
                'service_type' => $request->service_type,
                'service_image' => $request->service_image,
                'service_for' => $request->service_for,
                'slot_type' => json_encode($request->slot_type),
                'time_taken_to_complete_service' => $request->time_taken_to_complete_service,
                'service_created_by' => $request->user()->id,
            ]);
            if ($service->save()) {
                return response()->json([
                            'status' => true,
                            'message' => 'Service created successfully.',
                            'data' => []
                                ], 200);
            }
        } catch (\Exception $e) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * edit service
     */
    public function editService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required',
            'service_name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'service_image' => 'required|string',
            'service_for' => 'required|numeric',
            'service_type' => 'required_if:service_for,==,4',
            'slot_type' => 'required_if:service_for,==,4|array',
            'time_taken_to_complete_service' => 'required_if:service_for,==,4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()
            ], 422);
        }

        try {
            Service::whereId($request->service_id)->update([
                'service_name' => $request->service_name,
                'price' => $request->price,
                'description' => $request->description,
                'service_type' => $request->service_type,
                'service_image' => $request->service_image,
                'service_for' => $request->service_for,
                'slot_type' => json_encode($request->slot_type),
                'time_taken_to_complete_service' => $request->time_taken_to_complete_service,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Service edit successfully.',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * list services
     */
    public function listServices() {
//        $getAllServices = Service::get();
//        if (count($getAllServices) > 0) {
//            foreach ($getAllServices as $key => $service) {
//                if ($service->service_for == config('constant.roles.Customer')) {
//                    $timeSlots = TimeSlots::whereIn('id', json_decode($service->slot_time))->get();
//                    $getAllServices[$key]["timeSlots"] = $timeSlots;
//                }
//            }
//        }
        return response()->json([
            'status' => true,
            'message' => 'Service Listing.',
            'data' => Service::get()
        ], 200);
    }
    
    public function listServicesMobile(Request $request) {
        $validator = Validator::make($request->all(), [
                    'offset' => 'required',
                    'take' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        
        $getAllServices = Service::skip($request->offset)->take($request->take)->get();
        if (count($getAllServices) > 0) {
            foreach ($getAllServices as $key => $service) {
                if ($service->slot_type !== null) {
                    $slot_type = json_decode($service->slot_type);
                    $getAllServices[$key]["slot_type"] = $slot_type;
                }
            }
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
        
        if($fetchService != null) {
            $slot_type = json_decode($fetchService->slot_type);
            $fetchService["slot_type"] = $slot_type;
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
    public function getTimeSlots(Request $request) {
        $getTime = TimeSlots::whereSlotType($request->slot_type)->get();
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
}
