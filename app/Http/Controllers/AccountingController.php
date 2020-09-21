<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Mail;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Service;
use App\TimeSlots;
use App\Job;
use App\CustomerFarm;
use App\EmployeeSalaries;

class AccountingController extends Controller
{

    /**
     * get all jobs invoice
     */
    public function getAllJobInvoices() {
        return response()->json([
                    'status' => true,
                    'message' => 'job invoice Details',
                    'data' => Job::where('payment_status', config('constant.payment_status.paid'))->select('id', 'customer_id', 'service_id', 'amount', 'created_at')->with(['customer' => function($q) {
                            $q->select('id', 'first_name', 'last_name', 'email');
                        }])->with(['service' => function($q) {
                            $q->select('id', 'service_name');
                        }])->orderBy('created_at', 'DESC')->get()
                        ], 200);
    }

    /**
     * get all jobs payment
     */
    public function getAllJobPayment() {
        return response()->json([
                    'status' => true,
                    'message' => 'job payment Details',
                    'data' => Job::whereHas('customer', function($q) {
                                $q->where('role_id', 6);
                            })->where('payment_status', config('constant.payment_status.unpaid'))->select('id', 'customer_id', 'service_id', 'amount','payment_mode', 'created_at')->with(['customer' => function($q) {
                            $q->select('id', 'first_name', 'last_name', 'email');
                        }])->orderBy('created_at', 'DESC')->get()
                        ], 200);
    }
    
    public function getPayment(Request $request) {
        $validator = Validator::make($request->all(), [
                    'job_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'data' => $validator->errors()
                            ], 422);
        }
        $data['payment_status'] = config('constant.payment_status.paid');
        if(isset($request->payment_notes) && $request->payment_notes != '' && $request->payment_notes != null) {
            $data['payment_notes'] = $request->payment_notes;
        }
        if(Job::where('id', $request->job_id)->where('payment_status', config('constant.payment_status.unpaid'))->update($data)) {
            return response()->json([
                        'status' => false,
                        'message' => 'Payment updated sucessfully.',
                        'data' => []
                            ], 200);
        }
        return response()->json([
                        'status' => false,
                        'message' => 'Payment updation unsucessfull.',
                        'data' => []
                            ], 500);
    }

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * get all jobs salary
     */
    public function getAllJobSalary()
    {
        $getAllJobs = EmployeeSalaries::with("user.driver")
            ->selectRaw('year(created_at) year, monthname(created_at) month, sum(salary) salary, user_id')
            ->groupBy('year', 'month', 'user_id')
            ->orderBy('year', 'desc')->get();

        return response()->json([
            'status' => true,
            'message' => 'job salary Details',
            'data' => $getAllJobs
        ], 200);
    }

    /**
     * get all single jobs salary
     */
    public function getSingleJobSalary($driver_id)
    {
        $data = array();
        $getAllJobs = EmployeeSalaries::where('user_id', $driver_id)->with(["user.driver", "job.service"])->get();
        $total = EmployeeSalaries::where('user_id', $driver_id)->sum('salary');
        $driver = EmployeeSalaries::where('user_id', $driver_id)->with(["user.driver"])->first();
        $data['salary'] = $getAllJobs;
        $data['total'] = $total;
        $data['driver'] = $driver;
        return response()->json([
            'status' => true,
            'message' => 'job salary Details',
            'data' => $data
        ], 200);
    }

}
