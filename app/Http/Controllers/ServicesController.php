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
            'service_type' => 'required|numeric',
            'service_image' => 'required|string',
            'service_for' => 'required|numeric',
            'slot_type' => 'required_if:service_for,==,4|array',
            'slot_time' => 'required_if:service_for,==,4|array',
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
                'slot_time' => json_encode($request->slot_time),
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
            'service_type' => 'required|numeric',
            'service_image' => 'required|string',
            'service_for' => 'required|numeric',
            'slot_type' => 'required_if:service_for,==,4|array',
            'slot_time' => 'required_if:service_for,==,4|array',
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
                'slot_time' => json_encode($request->slot_time),
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
        $getAllServices = Service::get();
        if (count($getAllServices) > 0) {
            foreach ($getAllServices as $key => $service) {
                if ($service->service_for == config('constant.roles.Customer')) {
                    $timeSlots = TimeSlots::whereIn('id', json_decode($service->slot_time))->get();
                    $getAllServices[$key]["timeSlots"] = $timeSlots;
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
    public function getService(Request $request, $serviceId) {
        $fetchService = Service::whereId($serviceId)->first();
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
    public function deleteService(Request $request, $serviceId) {
        if(Service::whereId($serviceId)->delete()) {
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
