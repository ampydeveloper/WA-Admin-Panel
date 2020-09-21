<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\TrackDriverTime;
use App\User;
use App\Driver;
use App\Salary;

class SalaryController extends Controller
{
    public function calculateSalary()
    {
        try {
            //Transaction begin 
            DB::beginTransaction();
                
            $DateTime = Carbon::now();
            $cDrivers = Driver::where([
                ['salary_type', '=', config('constant.salary_type.per_hour')]
            ])->whereHas('driverTime', function($query) use($DateTime){
                $query->where([
                    ['month_number', '=', $DateTime->month],
                    ['is_settled', '=', 0]
                    ]);
            })->get();
            
            foreach($cDrivers as $Driver)
            {
                $iDriverTotalMonthlyTimeInSec = $Driver->filterDriverTime($Driver)->sum('total_time_in_second');
                
                $iSecondsInOneHour = 60*60;
                //salary calculation:
                $dDriverTotalMonthlySalary = (($Driver->driver_salary) * ($iDriverTotalMonthlyTimeInSec)) / ($iSecondsInOneHour);
                
                
                $DriverMonthlySalary = new Salary([
                    'driver_id' => $Driver->id,
                    'monthly_income' => $dDriverTotalMonthlySalary,
                    'row_action_count' => 1,
                    'month_number' => $DateTime->month,
                    'year_yyyy' => $DateTime->year,
                    
                ]);
                
                if($DriverMonthlySalary->save())
                {
                    $settleDriverTime = TrackDriverTime::where([
                        ['driver_id', $Driver->id],
                        ['month_number', $DateTime->month],
                        ])->update(['is_settled'=> 1]);
                    
                    if($settleDriverTime)
                    {
                       DB::commit();
                       Log::info('Salary row added successfully and marked as settle driver time.');
                    }
                }
            }
        
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error(json_encode($e->getMessage()));
        }
        
    }
}
