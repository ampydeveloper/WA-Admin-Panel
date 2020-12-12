<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{CustomerFarm,User,Service,Job};
use Carbon\Carbon;
use PDF;

class ReportController extends Controller
{
    public function getCustomerReport(Request $request)
    {
        //current month
        $firstDayofThisMonth = Carbon::now()->startOfMonth()->toDateString();
        $currentDayofThisMonth = Carbon::now()->toDateString();
        
        //previous month
        $firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
        $lastDayofPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();
        
        if($request->report_of == config("constant.report_of.this_month")) {
            $customerFarms = CustomerFarm::whereBetween('created_at', [$firstDayofThisMonth,$currentDayofThisMonth])->get();
        } else if($request->report_of == config("constant.report_of.last_month")) {
            $customerFarms = CustomerFarm::whereBetween('created_at', [$firstDayofPreviousMonth,$lastDayofPreviousMonth])->get();
        } else if($request->report_of == config("constant.report_of.this_year")) {
            $customerFarms = CustomerFarm::whereBetween('created_at', [Carbon::now()->year.'-01-01',$currentDayofThisMonth])->get();
        } else if($request->report_of == config("constant.report_of.last_year")) {
            $customerFarms = CustomerFarm::whereBetween('created_at', [date('Y', strtotime('last year')).'-01-01',date('Y', strtotime('last year')).'-12-31'])->get();
        } else if($request->report_of == config("constant.report_of.custom")) {
            $customerFarms = CustomerFarm::whereBetween('created_at', [$request->start_date,$request->end_date])->where('job_open')->get();
        }
        $now = date("Ymdhis");
        $pdf = PDF::loadView("pdf.customerFarms", compact('customerFarms'))->save(public_path().'/downloads/'.$now.'.pdf');
        
        $path = public_path() . '/downloads';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        
        return response()->json([
            'status' => true,
            'name' => "downloads/$now.pdf",
            'headers' => $headers
        ], 200);
        
        return response()->download($name, $headers);
        
        dump($customerFarms);
        dd($request->all());
        
    }

    public function salesByCustomer(Request $request)
    {
        $firstDayofThisMonth = Carbon::now()->startOfMonth()->toDateString();
        $currentDayofThisMonth = Carbon::now()->toDateString();
        
        //previous month
        $firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
        $lastDayofPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        //get eager loadded variables
        $callbackService = function($query) use($request) {
            $query->with('service');
        };
        
        if($request->report_of == config("constant.report_of.this_month")) {
            $saleCustomers = User::whereBetween('created_at', [$firstDayofThisMonth,$currentDayofThisMonth])->where('role_id',config('constant.roles.Customer'))->with(['jobs'=>$callbackService])->get();
        } else if($request->report_of == config("constant.report_of.last_month")) {
            $saleCustomers = User::whereBetween('created_at', [$firstDayofPreviousMonth,$lastDayofPreviousMonth])->where('role_id',config('constant.roles.Customer'))->with(['jobs'=>$callbackService])->get();
        } else if($request->report_of == config("constant.report_of.this_year")) {
            $saleCustomers = User::whereBetween('created_at', [Carbon::now()->year.'-01-01',$currentDayofThisMonth])->where('role_id',config('constant.roles.Customer'))->with(['jobs'=>$callbackService])->get();
        } else if($request->report_of == config("constant.report_of.last_year")) {
            $saleCustomers = User::whereBetween('created_at', [date('Y', strtotime('last year')).'-01-01',date('Y', strtotime('last year')).'-12-31'])->where('role_id',config('constant.roles.Customer'))->with(['jobs'=>$callbackService])->get();
        } else if($request->report_of == config("constant.report_of.custom")) {
            
            $saleCustomers = User::whereBetween('created_at', [$request->start_date,$request->end_date])
            ->where('role_id',config('constant.roles.Customer'));
            
            if($request->job_status) {
                $saleCustomers->whereHas('jobs',function($query) use($request){
                    $query->whereIn('job_status',$request->job_status);
                });
            } 
            if($request->name) {
                $name = $this->__getName($request);
                if(isset($name) && !empty($name)){
                    $saleCustomers->where('first_name',$name[0])
                    ->where('last_name',$name[1]);
                } else {
                    $saleCustomers->where('first_name',$request->name);
                }
            } 
            if($request->tech_assigned) { 
                $saleCustomers->whereHas('jobs',function($query1) use($request){
                    $query1->where(function($j) use($request){
                        $j->orWhereIn('truck_driver_id',$request->tech_assigned)
                        ->orWhereIn('skidsteer_driver_id',$request->tech_assigned);
                    });
                });
            }
            $saleCustomers->with('jobs','jobs.service')->get();
        }
        // $filteredSaleJobs = $saleCustomers->filter(function ($user, $key) {
        //     return $user->customer != null;
        // });

        return response()->json([
            'status' => true,
            'saleCustomers' => $saleCustomers
        ], 200);
    }

    private function __getName($request)
    {
        $name = '';
        if (strpos($request->name, " ") !== false)
            $name = explode(' ',$request->name);

        return $name;
    }

    public function salesByServiceTech(Request $request)
    {
        $firstDayofThisMonth = Carbon::now()->startOfMonth()->toDateString();
        $currentDayofThisMonth = Carbon::now()->toDateString();
        
        //previous month
        $firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
        $lastDayofPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        if($request->report_of == config("constant.report_of.this_month")) {
            $saleJobs = Job::whereBetween('created_at', [$firstDayofThisMonth,$currentDayofThisMonth])->with('service','jobpayment','customer')->get();
        } else if($request->report_of == config("constant.report_of.last_month")) {
            $saleJobs = Job::whereBetween('created_at', [$firstDayofPreviousMonth,$lastDayofPreviousMonth])->with('service','jobpayment','customer')->get();
        } else if($request->report_of == config("constant.report_of.this_year")) {
            $saleJobs = Job::whereBetween('created_at', [Carbon::now()->year.'-01-01',$currentDayofThisMonth])->with('service','jobpayment','customer')->get();
        } else if($request->report_of == config("constant.report_of.last_year")) {
            $saleJobs = Job::whereBetween('created_at', [date('Y', strtotime('last year')).'-01-01',date('Y', strtotime('last year')).'-12-31'])->with('service','jobpayment','customer')->get();
        } else if($request->report_of == config("constant.report_of.custom")) {
            $saleJobs = Job::whereBetween('created_at', [$request->start_date,$request->end_date]);

            if($request->job_status) {
                $saleJobs->whereIn('job_status',$request->job_status);
            }
            if($request->name) {
                $name = $this->__getName($request);
                if(isset($name) && !empty($name)) {
                    $saleJobs->whereHas('customer',function($query1) use($request,$name) {
                        $query1->where('first_name',$name[0])
                        ->where('last_name',$name[1]);
                    });
                } else {
                    $saleJobs->whereHas('customer',function($query) use($request) {
                        $query->where('first_name',$request->name);
                    });
                }
            }
            if($request->tech_assigned) {
                $saleJobs->where(function($a) use($request) {
                    $a->orWhereIn('truck_driver_id',$request->tech_assigned)
                    ->orWhereIn('skidsteer_driver_id',$request->tech_assigned);
                });
            }
            
            $saleJobs->with(['service','jobpayment','customer'])->get();
        }
        $filteredSaleJobs = $saleJobs->filter(function ($user, $key) {
            return $user->customer != null;
        });
        // foreach($filteredSaleJobs as $j){
        //     dump($j->job_status);
        // }
        return response()->json([
            'status' => true,
            'saleJobs' => $filteredSaleJobs
        ], 200);
       
    }

    public function transactionsByCustomer(Request $request)
    {
        $now = Carbon::now();
        $lastTwelveMonth = Carbon::now()->startOfMonth()->subMonth(12);
        $weekEndDate = Carbon::now()->endOfWeek()->format('Y-m-d');
        $firstDayofThisMonth = Carbon::now()->startOfMonth()->toDateString();
        $currentDayofThisMonth = Carbon::now()->toDateString();
        
        //previous month
        $firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
        $lastDayofPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        
        if($request->report_of == config("constant.report_of.this_month")) {
            $customerTransactions = User::whereBetween('created_at', [$firstDayofThisMonth,$currentDayofThisMonth])->where('role_id',config('constant.roles.Customer'))->with(['jobs','jobs.jobpayment','payment'])->get();
        } else if($request->report_of == config("constant.report_of.last_month")) {
            $customerTransactions = User::whereBetween('created_at', [$firstDayofPreviousMonth,$lastDayofPreviousMonth])->where('role_id',config('constant.roles.Customer'))->with(['jobs','jobs.jobpayment','payment'])->get();
        } else if($request->report_of == config("constant.report_of.this_year")) {
            $customerTransactions = User::whereBetween('created_at', [Carbon::now()->year.'-01-01',$currentDayofThisMonth])->where('role_id',config('constant.roles.Customer'))->with(['jobs','jobs.jobpayment','payment'])->get();
        } else if($request->report_of == config("constant.report_of.last_twelve_month")) {
            $customerTransactions = User::whereBetween('created_at', [$lastTwelveMonth,$currentDayofThisMonth])->where('role_id',config('constant.roles.Customer'))->with(['jobs','jobs.jobpayment','payment'])->get();
        } else if($request->report_of == config("constant.report_of.this_week")) {
            $customerTransactions = User::whereBetween('created_at', [$now->startOfWeek()->format('Y-m-d'),$now->endOfWeek()->format('Y-m-d')])->where('role_id',config('constant.roles.Customer'))->with(['jobs','jobs.jobpayment','payment'])->get();
        } else if($request->report_of == config("constant.report_of.custom")) {

            $customerTransactions = User::whereBetween('created_at', [$request->start_date,$request->end_date])
            ->where('role_id',config('constant.roles.Customer'));
            if($request->payment_methods) {
                $customerTransactions->WhereIn('payment_mode',$request->payment_methods);
            }
            if($request->transaction_type) {
                $customerTransactions->whereHas('jobs',function($q) use($request) {
                    $q->WhereIn('payment_status',$request->transaction_type);
                });
            } 
            if($request->name) {
                $name = $this->__getName($request);
                
                if(isset($name) && !empty($name)){
                    $customerTransactions->where('first_name',$name[0])
                    ->where('last_name',$name[1]);
                } else {
                    $customerTransactions->where('first_name',$request->name);
                }
            }
            if($request->job) {
                $customerTransactions->whereHas('jobs',function($query2) use($request) {
                    $query2->where('id',$request->job);
                });
            }
            if($request->tech_assigned) {
                $customerTransactions->whereHas('jobs',function($query3) use($request) {
                    $query3->where(function($j) use($request){
                        $j->whereIn('truck_driver_id',$request->tech_assigned)
                        ->orWhereIn('skidsteer_driver_id',$request->tech_assigned);
                    });
                });
            }
            $a = $customerTransactions->with(['jobs','jobs.jobpayment','payment'])->get();
        }

        return response()->json([
            'status' => true,
            'customerTransactions' => $a
        ], 200);
    }
}
