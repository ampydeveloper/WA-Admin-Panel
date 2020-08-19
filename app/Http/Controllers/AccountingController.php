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
    public function getAllJobInvoices()
    {
        $getAllJobs = Job::with(
            "customer",
            "service"
        )->get();

        return response()->json([
            'status' => true,
            'message' => 'job invoice Details',
            'data' => $getAllJobs
        ], 200);
    }

    /**
     * get all jobs payment
     */
    public function getAllJobPayment()
    {
        $getAllJobs = Job::where('payment_status', 1)->with(
            "jobpayment",
            "customer"
        )->get();

        return response()->json([
            'status' => true,
            'message' => 'job payment Details',
            'data' => $getAllJobs
        ], 200);
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
