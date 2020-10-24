<?php

namespace App\Http\Controllers;

use Mail;
use App\Job;
use App\User;
use Validator;
use App\Salary;
use App\Service;
use App\TimeSlots;
use App\CustomerFarm;
use App\EmployeeSalaries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{

    /**
     * get all jobs invoice
     */
    public function getAllJobInvoices() {
        return response()->json([
                    'status' => true,
                    'message' => 'job invoice Details',
                    'data' => Job::where('payment_status', config('constant.payment_status.paid'))->select('id', 'customer_id', 'service_id', 'amount', 'payment_mode','job_status','payment_status','quick_book', 'created_at')->with(['customer' => function($q) {
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
    public function getAllJobSalary() {
        $month = date('m');
        return response()->json([
                    'status' => true,
                    'message' => 'job salary Details',
                    'data' => User::where('id',28)->where('role_id', config('constant.roles.Driver'))->select('id', 'first_name', 'last_name', 'phone', 'role_id')->with(['driver' => function($q) use($month) {
                            $q->select('id', 'user_id')->with(['salary' => function($q) use($month) {
                                    $q->where('month_number', $month - 1);
                                }]);
                        }])->get()
                        ], 200);
    }
    
    public function paySalary(Request $request) {
        if(Salary::where('id', $request->driver_id)->where('is_settled', 0)->update(['is_settled' => 1])) {
            return response()->json([
                        'status' => false,
                        'message' => 'Salary paid sucessfully.',
                        'data' => []
                            ], 200);
        }
        return response()->json([
                        'status' => false,
                        'message' => 'Salary paid unsucessfull.',
                        'data' => []
                            ], 500);
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
