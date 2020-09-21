<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\TrackDriverTime;
use App\User;
use App\Driver;

class TrackDriverTimeController extends Controller
{
    public function startJobTimer(Request $request) {
       
        $validator = Validator::make($request->all(), [
                    'driver_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data is invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        
        try {
            
            $DateTime = Carbon::now();
            
            $trackDriverTime = new TrackDriverTime([
                'driver_id' => $request->driver_id,
                'row_action_count' => 1,
                'start_time' => $DateTime->toDateTimeString(),
                'day_number'=> $DateTime->day,
                'month_number'=> $DateTime->month,
                'year_yyyy' => $DateTime->year,
            ]);
             
            if($trackDriverTime->save())
            {
                return response()->json([
                        'status' => true,
                        'message' => 'Job timer started',
                        ], 200);
            }
            else{
                return response()->json([
                        'status' => false,
                        'message' => 'Job timer not started. Please try again later.',
                            ], 422);
            }
        }
        catch (\Exception $ex) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        ], 500);
        }      
    }
    
    public function stopJobTimer(Request $request) {
       
        $validator = Validator::make($request->all(), [
                    'driver_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data is invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        
        try {
            $DateTime = Carbon::now();
            
            $trackDriverTime = TrackDriverTime::where([
                ['driver_id', '=', $request->driver_id],
                ['start_time', '!=', NULL],
                ['day_number', '=', $DateTime->day],
                ['month_number', '=', $DateTime->month],
                ['is_settled', '=', 0]
            ])->orderBy('updated_at', 'desc')->first();
            
            $trackDriverTime->stop_time = $DateTime->toDateTimeString();
            $trackDriverTime->total_time_in_second = $trackDriverTime->stop_time->diffInSeconds($trackDriverTime->start_time);
            $trackDriverTime->row_action_count += 1;
            
            if($trackDriverTime->save())
            {
                return response()->json([
                        'status' => true,
                        'message' => 'Job timer stopped',
                        ], 200);
            }
            else{
                return response()->json([
                        'status' => false,
                        'message' => 'Job timer not stopped. Please try again later.',
                            ], 422);
            }
        }
        catch (\Exception $ex) {
            Log::error(json_encode($e->getMessage()));
            return response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                        ], 500);
        }      
    }
}

