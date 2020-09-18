<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Validator;
use App\User;
use App\Vehicle;
use App\VehicleService;
use App\VehicleInsurance;

class VehicleController extends Controller {
    /**
     * list trucks
     */
    public function listVehicle() {
        return response()->json([
                    'status' => true,
                    'message' => 'Truck Details',
                    'data' => Vehicle::with("vehicle_service", "vehicle_insurance")->whereVehicleType(config("constant.vehicle_type.truck"))->get()
                        ], 200);
    }
    
    public function listVehicleMobile(Request $request) {
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
        return response()->json([
                    'status' => true,
                    'message' => 'Truck Details',
                    'data' => Vehicle::with("vehicle_service", "vehicle_insurance")->whereVehicleType(config("constant.vehicle_type.truck"))->skip($request->offset)->take($request->take)->get()
                        ], 200);
    }
    /**
     * create vehicle
     */
    public function createVehicle(Request $request) {
        $validator = Validator::make($request->all(), [
                    'vehicle_type' => 'required',
                    'company_name' => 'required|string',
                    'truck_number' => 'required',
                    'chaase_number' => 'required',
                    'killometer' => 'required',
                    'rc_document' => 'required',
                    'insurance_date' => 'required',
                    'insurance_expiry' => 'required',
                    'insurance_number' => 'required',
                    'insurance_document' => 'required',
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
            $vechicle = new Vehicle([
                'created_by' => $request->user()->id,
                'vehicle_type' => $request->vehicle_type,
                'company_name' => $request->company_name,
                'truck_number' => $request->truck_number,
                'chaase_number' => $request->chaase_number,
                'killometer' => $request->killometer,
                'capacity' => (isset($request->capacity) && $request->capacity != '' && $request->capacity != null) ? $request->capacity : null,
                'document' => $request->rc_document,
                'status' => config('constant.vehicle_status.available'),
            ]);
            if ($vechicle->save()) {
                $vechicleInsurance = new VehicleInsurance([
                    'vehicle_id' => $vechicle->id,
                    'created_by' => $request->user()->id,
                    'insurance_date' => $request->insurance_date,
                    'insurance_expiry' => $request->insurance_expiry,
                    'insurance_number' => $request->insurance_number,
                    'document' => $request->insurance_document,
                    'notes' => (isset($request->notes) && $request->notes != '' && $request->notes != null) ? $request->notes : null,
                ]);
                if ($vechicleInsurance->save()) {
                    DB::commit();
                    return response()->json([
                                'status' => true,
                                'message' => 'Vehicle created successfully.',
                                'data' => []
                                    ], 200);
                }
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
    /**
     * edit vehicle
     */
    public function editVehicle(Request $request) {
        $validator = Validator::make($request->all(), [
                    'vehicle_id' => 'required',
                    'vehicle_type' => 'required',
                    'company_name' => 'required|string',
                    'truck_number' => 'required',
                    'chaase_number' => 'required',
                    'killometer' => 'required',
                    'rc_document' => 'required',
                    'is_active' => 'required',
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
            $updateVehicle = Vehicle::whereId($request->vehicle_id)->update([
                'company_name' => $request->company_name,
                'truck_number' => $request->truck_number,
                'chaase_number' => $request->chaase_number,
                'killometer' => $request->killometer,
                'capacity' => (isset($request->capacity) && $request->capacity != '' && $request->capacity != null) ? $request->capacity : null,
                'document' => $request->rc_document,
                'status' => $request->is_active
            ]);
            DB::commit();
            return response()->json([
                        'status' => true,
                        'message' => 'Vehicle details edit successfully.',
                        'data' => []
                            ], 200);
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
    /**
     * get vehicle
     */
    public function getVehicle(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Vehicle Details',
                    'data' => Vehicle::with("vehicle_service", "vehicle_insurance")->whereId($request->vehicle_id)->first()
                        ], 200);
    }
    /**
     * delete vehicle
     */
    public function deleteVehicle(Request $request) {
        try {
            Vehicle::whereId($request->vehicle_id)->delete();
            return response()->json([
                        'status' => true,
                        'message' => 'Vehicle deleted successfully.',
                        'data' => []
                            ], 200);
        } catch (\Exception $e) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => json_encode($e->getMessage()),
                        'data' => []
                            ], 500);
        }
    }
    /**
     * create vehicle insurance
     */
    public function createVehicleInsurance(Request $request) {
        $validator = Validator::make($request->all(), [
                    'vehicle_id' => 'required',
                    'insurance_date' => 'required',
                    'insurance_expiry' => 'required',
                    'insurance_number' => 'required',
                    'insurance_document' => 'required'
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
            $vechicle = new VehicleInsurance([
                'vehicle_id' => $request->vehicle_id,
                'created_by' => $request->user()->id,
                'insurance_date' => $request->insurance_date,
                'insurance_expiry' => $request->insurance_expiry,
                'insurance_number' => $request->insurance_number,
                'document' => $request->insurance_document,
                'notes' => (isset($request->notes) && $request->notes != '' && $request->notes != null) ? $request->notes : null,
            ]);
            if($vechicle->Save()) {
                DB::commit();
                return response()->json([
                            'status' => true,
                            'message' => 'Successfully created vehicle insurance.',
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

    public function editVehicleInsurance(Request $request) {
        $validator = Validator::make($request->all(), [
                    'vehicle_insurance_id' => 'required',
                    'insurance_date' => 'required',
                    'insurance_expiry' => 'required',
                    'insurance_number' => 'required',
                    'insurance_document' => 'required',
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
            $updateVehicleInsurance = VehicleInsurance::whereId($request->vehicle_insurance_id)->update([
                'insurance_date' => $request->insurance_date,
                'insurance_expiry' => $request->insurance_expiry,
                'insurance_number' => $request->insurance_number,
                'document' => $request->insurance_document,
                'notes' => (isset($request->notes) && $request->notes != '' && $request->notes != null) ? $request->notes : null,
            ]);
            DB::commit();
            return response()->json([
                        'status' => true,
                        'message' => 'Vehicle insurance details edit successfully.',
                        'data' => []
                            ], 200);
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
    /**
     * get insurance details
     */
    public function getInsuranceDetails(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Insurance Details',
                    'data' => VehicleInsurance::whereId($request->insurance_id)->with('vehicle')->first()
                        ], 200);
    }

    public function getLastInsurance(Request $request) {
        $vehicleInsu = VehicleInsurance::where('vehicle_id', $request->vehicle_id)->with('vehicle')->latest()->first();
        return response()->json([
                    'status' => true,
                    'message' => 'Vehicle latest insurance.',
                    'data' => $vehicleInsu
                        ], 200);
    }

    /**
     * get vehivle service details
     * param @vehicle_id
     * return array
     */
    public function getVehicleInsurance(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Vehicle insurance with vehicle',
                    'data' => VehicleInsurance::where('vehicle_id', $request->vehicle_id)->get()
                        ], 200);
    }

    public function getVehicleInsuranceMobile(Request $request) {
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
        return response()->json([
                    'status' => true,
                    'message' => 'Vehicle insurance with vehicle',
                    'data' => VehicleInsurance::where('vehicle_id', $request->vehicle_id)->skip($request->offset)->take($request->take)->get()
                        ], 200);
    }
    /**
     * delete vehicle insurance details
     */
    public function deleteInsuranceDetails(Request $request) {
        try {
            VehicleInsurance::whereId($request->insurance_id)->delete();
            return response()->json([
                        'status' => true,
                        'message' => 'Vehicle insurance deleted Successfully.',
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
     * create vehicle service
     */
    public function createVehicleService(Request $request) {
        $validator = Validator::make($request->all(), [
                    'vehicle_id' => 'required',
                    'service_date' => 'required',
                    'service_killometer' => 'required',
                    'receipt' => 'required',
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
            $vechicle = new VehicleService([
                'vehicle_id' => $request->vehicle_id,
                'created_by' => $request->user()->id,
                'service_killometer' => $request->service_killometer,
                'service_date' => $request->service_date,
                'receipt' => $request->receipt,
                'document' => (isset($request->document) && $request->document != '' && $request->document != null) ? $request->document : null,
                'notes' => (isset($request->notes) && $request->notes != '' && $request->notes != null) ? $request->notes : null,
            ]);
            if($vechicle->Save()) {
                DB::commit();
                return response()->json([
                            'status' => true,
                            'message' => 'Vehicle service created successfully.',
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

    public function editVehicleService(Request $request) {
        $validator = Validator::make($request->all(), [
                    'vehicle_service_id' => 'required',
                    'service_date' => 'required',
                    'service_killometer' => 'required',
                    'receipt' => 'required',
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
            $updateVehicleInsurance = VehicleService::whereId($request->vehicle_service_id)->update([
                'service_killometer' => $request->service_killometer,
                'service_date' => $request->service_date,
                'receipt' => $request->receipt,
                'document' => (isset($request->document) && $request->document != '' && $request->document != null) ? $request->document : null,
                'notes' => (isset($request->notes) && $request->notes != '' && $request->notes != null) ? $request->notes : null,
            ]);
            DB::commit();
            return response()->json([
                        'status' => true,
                        'message' => 'Vehicle insurance details edited successfully.',
                        'data' => []
                            ], 200);
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        'data' => []
                            ], 500);
        }
    }
    /**
     * get vehicle service details
     * return array
     */
    public function getVehicleService(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Vehicle Details',
                    'data' => VehicleService::where('vehicle_id', $request->vehicle_id)->get()
                        ], 200);
    }
    
    public function getVehicleServiceMobile(Request $request) {
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
        return response()->json([
                    'status' => true,
                    'message' => 'Vehicle Details',
                    'data' => VehicleService::where('vehicle_id', $request->vehicle_id)->skip($request->offset)->take($request->take)->get()
                        ], 200);
    }
    /**
     * get service details
     */
    public function getServiceDetails(Request $request) {
        return response()->json([
                    'status' => true,
                    'message' => 'Service Details',
                    'data' => VehicleService::whereId($request->service_id)->first()
                        ], 200);
    }
    
    public function getLastService(Request $request) {
        $vehicleInsu = VehicleService::where('vehicle_id', $request->vehicle_id)->latest()->first();
        return response()->json([
                    'status' => true,
                    'message' => 'Vehicle latest insurance.',
                    'data' => $vehicleInsu
                        ], 200);
    }
    /**
     * delete vehicle service details
     */
    public function deleteServiceDetails(Request $request) {
        try {
            VehicleService::whereId($request->service_id)->delete();
            return response()->json([
                        'status' => true,
                        'message' => 'Vehicle service deleted successfully.',
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
     * list skidsteer
     */
    public function listSkidsteer() {
        return response()->json([
                    'status' => true,
                    'message' => 'Skidsteer Details',
                    'data' => Vehicle::with("user", "vehicle_service", "vehicle_insurance")->whereVehicleType(config("constant.vehicle_type.skidsteer"))->get()
                        ], 200);
    }

    /**
     * save service details
     */
//    public function saveServiceDetails(Request $request) {
//        try {
//            VehicleService::whereId($request->service_id)->update([
//                'service_killometer' => $request->service_killometer,
//                'service_date' => $request->service_date,
//                'receipt' => $request->receipt,
//                'document' => $request->document,
//                'note' => $request->note,
//            ]);
//
//            return response()->json([
//                        'status' => true,
//                        'message' => 'Vehicle Service Updated Successfully',
//                        'data' => []
//                            ], 200);
//        } catch (\Exception $e) {
//            //make log of errors
//            Log::error(json_encode($e->getMessage()));
//            //return with error
//            return response()->json([
//                        'status' => false,
//                        'message' => 'Internal server error!',
//                        'data' => []
//                            ], 500);
//        }
//    }
//
//    /**
//     * save insurance details
//     */
//    public function saveInsuranceDetails(Request $request) {
//        try {
//            VehicleInsurance::whereId($request->insurance_id)->update([
//                'insurance_number' => $request->insurance_number,
//                'insurance_date' => $request->insurance_date,
//                'insurance_expiry' => $request->insurance_expiry
//            ]);
//
//            return response()->json([
//                        'status' => true,
//                        'message' => 'Vehicle Insurance Updated Successfully',
//                        'data' => []
//                            ], 200);
//        } catch (\Exception $e) {
//            //make log of errors
//            Log::error(json_encode($e->getMessage()));
//            //return with error
//            return response()->json([
//                        'status' => false,
//                        'message' => 'Internal server error!',
//                        'data' => []
//                            ], 500);
//        }
//    }

}
