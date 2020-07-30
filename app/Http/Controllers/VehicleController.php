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

class VehicleController extends Controller
{
    /**
     * create vehicle
     */
    public function createVehicle(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string',
            'truck_number' => 'required',
            'chaase_number' => 'required',
            'insurance_number' => 'required',
            'insurance_date' => 'required',
            'insurance_expiry' => 'required'
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

            //create new vehicle
            $vechicle = new Vehicle([
                'created_by' => $request->user()->id,
                'vehicle_type' => $request->vehicle_type,
                'company_name' => $request->company_name,
                'truck_number' => $request->truck_number,
                'chaase_number' => $request->chaase_number,
                'killometer' => $request->total_killometer,
                'capacity' => $request->capacity,
                'document' => $request->document,
                'insurance_document' => $request->insurance_document,
		'status' => $request->is_active
            ]);
            //save vehicle
            if($vechicle->save()) {
                $vechicleInsurance = new VehicleInsurance([
                    'vehicle_id' => $vechicle->id,
                    'insurance_number' => $request->insurance_number,
                    'insurance_date' => $request->insurance_date,
                    'insurance_expiry' => $request->insurance_expiry
                ]);

                $vechicleInsurance->save();
            }
            //commit all transactions now
            DB::commit();
            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Successfully created Vehicle!',
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
     * edit vehicle
     */
    public function editVehicle(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string',
            'truck_number' => 'required',
            'chaase_number' => 'required',
            'insurance_number' => 'required',
            'insurance_date' => 'required',
            'insurance_expiry' => 'required'
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

            //update vehicle table
            $updateVehicle = Vehicle::whereId($request->vehicle_id)->update([
                'company_name' => $request->company_name,
                'truck_number' => $request->truck_number,
                'chaase_number' => $request->chaase_number,
                'killometer' => $request->total_killometer,
	        'capacity' => $request->capacity,
                'document' => $request->document,
	        'insurance_document' => $request->insurance_document,
		'status' => $request->is_active
            ]);
           
            //save insurance details
            VehicleInsurance::whereVehicleId($request->vehicle_id)->update([
                'insurance_number' => $request->insurance_number,
                'insurance_date' => $request->insurance_date,
                'insurance_expiry' => $request->insurance_expiry
            ]);

            //commit all transactions now
            DB::commit();
            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Vehicle details edit successfully.',
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
     * list trucks
     */
    public function listVehicle() {
        return response()->json([
            'status' => true,
            'message' => 'Truck Details',
            'data' => Vehicle::with("user", "vehicle_service", "vehicle_insurance")->whereVehicleType(config("constant.vehicle_type.truck"))->get()
        ], 200);    
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
     * get vehicle
     */
    public function getVehicle(Request $request) {
        return response()->json([
            'status' => true,
            'message' => 'Vehicle Details',
            'data' => Vehicle::with("user", "vehicle_service", "vehicle_insurance")->whereId($request->vehicle_id)->first()
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
                'message' => 'Vehicle deleted Successfully',
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
    * get vehivle service details
    * param @vehicle_id
    * return array
    */
    public function getVehicleService(Request $request)
    {
         return response()->json([
            'status' => true,
            'message' => 'Vehicle Details',
            'data' => VehicleService::where('vehicle_id', $request->vehicle_id)->get()
        ], 200);
    } 
    /**
     * create vehicle service
     */
    public function createVehicleService(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'total_killometer' => 'required',
            'service_date' => 'required'
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

            //create new vehicle serive
            $vechicle = new VehicleService([
                'vehicle_id' => $request->vehicle_id,
                'service_date' => $request->service_date,
                'service_killometer' => $request->total_killometer,
                'receipt' => $request->receipt,
                'document' => $request->document, 
                'note' => $request->note,
            ]);
	    $vechicle->Save();
            //commit all transactions now
            DB::commit();
            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Successfully created Vehicle service!',
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
    * get vehivle service details
    * param @vehicle_id
    * return array
    */
    public function getVehicleInsurance(Request $request)
    {
         return response()->json([
            'status' => true,
            'message' => 'Vehicle Details',
            'data' => VehicleInsurance::where('vehicle_id', $request->vehicle_id)->get()
        ], 200);
    } 

    /**
     * create vehicle insurance
     */
    public function createVehicleInsurance(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'insurance_number' => 'required',
            'insurance_date' => 'required',
            'insurance_expiry' => 'required'
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

            //create new vehicle insurance
            $vechicle = new VehicleInsurance([
                'vehicle_id' => $request->vehicle_id,
                'insurance_date' => $request->insurance_date,
                'insurance_expiry' => $request->insurance_expiry,
                'insurance_number' => $request->insurance_number,
            ]);

	    $vechicle->Save();
            //commit all transactions now
            DB::commit();
            //return success response
            return response()->json([
                'status' => true,
                'message' => 'Successfully created Vehicle insurance!',
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
     * get service details
     */
    public function getServiceDetails(Request $request) {
        return response()->json([
            'status' => true,
            'message' => 'Service Details',
            'data' => VehicleService::whereId($request->service_id)->first()
        ], 200);    
    }

    /**
     * get insurance details
     */
    public function getInsuranceDetails(Request $request) {
        return response()->json([
            'status' => true,
            'message' => 'Insurance Details',
            'data' => VehicleInsurance::whereId($request->insurance_id)->first()
        ], 200);    
    }

    /**
     * save service details
     */
    public function saveServiceDetails(Request $request) {
        try {
            VehicleService::whereId($request->service_id)->update([
                'service_killometer' => $request->service_killometer,
                'service_date' => $request->service_date,
                'receipt' => $request->receipt,
                'document' => $request->document,
                'note' => $request->note,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Vehicle Service Updated Successfully',
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
     * save insurance details
     */
    public function saveInsuranceDetails(Request $request) {
        try {
            VehicleInsurance::whereId($request->insurance_id)->update([
                'insurance_number' => $request->insurance_number,
                'insurance_date' => $request->insurance_date,
                'insurance_expiry' => $request->insurance_expiry
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Vehicle Insurance Updated Successfully',
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

    public function getLastInsurance(Request $request){
	$vehicleInsu = VehicleInsurance::where('vehicle_id', $request->vehicle_id)->latest()->first();
        return response()->json([
                'status' => true,
                'message' => 'Vehicle service deleted successfully',
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
                'message' => 'Vehicle service deleted successfully',
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
     * delete vehicle insurance details
     */
    public function deleteInsuranceDetails(Request $request) {
        try {
            VehicleInsurance::whereId($request->insurance_id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Vehicle insurance deleted Successfully',
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
}
