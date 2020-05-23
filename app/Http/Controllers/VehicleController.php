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
                // 'capacity' => $request->insurance_date,
                'document' => $request->document
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
                'killometer' => $request->total_killometer
            ]);
            //check if document is also uploaded    
            if($request->document != '' && $request->document != null) {
                $updateVehicle->document = $request->document;
                $updateVehicle->save();
            }

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
}
