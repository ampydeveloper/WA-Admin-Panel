<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\{CustomerFarm,User,Service,Job};
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    
    
    public function salesByCustomer(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
                    'report_of' => 'required',
                    'start_date' => 'required_if:report_of,==,'.config("constant.report_of.custom"),
                    'end_date' => 'required_if:report_of,==,'.config("constant.report_of.custom"),
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'status' => false,
                        'message' => $validator->errors(),
                        'data' => []
                            ], 422);
        }
        
        if($request->report_of == config("constant.report_of.this_week")) {
            
            $firstDay = Carbon::now()->startOfWeek()->toDateString();
            $lastDay = Carbon::now()->toDateString();
            $saleCustomers = $this->__getSalesByCustomerQuery($firstDay,$lastDay);
            
        } else if($request->report_of == config("constant.report_of.this_month")) {
            
            $firstDay = Carbon::now()->startOfMonth()->toDateString();
            $lastDay = Carbon::now()->toDateString();
            $saleCustomers = $this->__getSalesByCustomerQuery($firstDay,$lastDay);
            
        } elseif($request->report_of == config("constant.report_of.last_month")) {
            
            $firstDay = Carbon::now()->startOfMonth()->subMonth()->toDateString();
            $lastDay = Carbon::now()->subMonth()->endOfMonth()->toDateString();
            $saleCustomers = $this->__getSalesByCustomerQuery($firstDay,$lastDay);
            
        } elseif($request->report_of == config("constant.report_of.this_year")) {
            
            $firstDay = Carbon::now()->year.'-01-01';
            $lastDay = Carbon::now()->toDateString();
            $saleCustomers = $this->__getSalesByCustomerQuery($firstDay,$lastDay);
                
            
        } elseif($request->report_of == config("constant.report_of.last_year")) {

            $firstDay = date('Y', strtotime('last year')).'-01-01';
            $lastDay = date('Y', strtotime('last year')).'-12-31';
            $saleCustomers = $this->__getSalesByCustomerQuery($firstDay,$lastDay);
            
        } elseif($request->report_of == config("constant.report_of.last_twelve_month")) {
            
            $firstDay = Carbon::now()->startOfMonth()->subMonth(12);
            $lastDay = Carbon::now()->toDateString();
            $saleCustomers = $this->__getSalesByCustomerQuery($firstDay,$lastDay);
            
        } else {
            
            $firstDay = $request->start_date;
            $lastDay = $request->end_date;
            $saleCustomers = $this->__getSalesByCustomerQuery($firstDay,$lastDay);

        }

        return response()->json([
            'status' => true,
            'saleCustomers' => $saleCustomers
        ], 200);
    }
    
    private function __getSalesByCustomerQuery($firstDay,$lastDay)
    {
        $data = User::whereHas('jobs', function($q) use($firstDay, $lastDay) {
            $q->whereBetween('created_at', [$firstDay, $lastDay]);
            })->select('id', 'first_name', 'last_name','created_at')->with(['jobs' => function($q) {
                $q->select('id', 'customer_id', 'service_id', 'job_providing_date', 'job_providing_time', 'amount', 'job_status', 'payment_status', 'quick_book', 'truck_driver_id', 'skidsteer_driver_id','created_at')->with(['truck_driver' => function($q) {
                        $q->select('id', 'first_name', 'last_name');
                    }, 'skidsteer_driver' => function($q) {
                        $q->select('id', 'first_name', 'last_name');
                    },'service'=>function($query){
                        $query->select('id','service_name');
                    }]);
            }])->withCount(['jobs','jobs as sum'=>function($w){
                $w->select(\DB::raw("SUM(amount) as sum"));
            }])->get();

            return $data;
    }
    
    
    public function salesByServiceTech(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'report_of' => 'required',
            'start_date' => 'required_if:report_of,==,'.config("constant.report_of.custom"),
            'end_date' => 'required_if:report_of,==,'.config("constant.report_of.custom")
            ]);
            if ($validator->fails()) {
                return response()->json([
                            'status' => false,
                            'message' => $validator->errors(),
                            'data' => []
                                ], 422);
            }

        if($request->report_of == config("constant.report_of.this_week")) {
            
            $firstDay = Carbon::now()->startOfWeek()->toDateString();
            $lastDay = Carbon::now()->toDateString();
            $saleJobs = $this->__saleByServiceTechQuery($firstDay,$lastDay);
            
        } else if($request->report_of == config("constant.report_of.this_month")) {
            
            $firstDay = Carbon::now()->startOfMonth()->toDateString();
            $lastDay = Carbon::now()->toDateString();
            $saleJobs = $this->__saleByServiceTechQuery($firstDay,$lastDay);
            
        } elseif($request->report_of == config("constant.report_of.last_month")) {
            
            $firstDay = Carbon::now()->startOfMonth()->subMonth()->toDateString();
            $lastDay = Carbon::now()->subMonth()->endOfMonth()->toDateString();
            $saleJobs = $this->__saleByServiceTechQuery($firstDay,$lastDay);
            
        } elseif($request->report_of == config("constant.report_of.this_year")) {
            
            $firstDay = Carbon::now()->year.'-01-01';
            $lastDay = Carbon::now()->toDateString();
            $saleJobs = $this->__saleByServiceTechQuery($firstDay,$lastDay);
                
            
        } elseif($request->report_of == config("constant.report_of.last_year")) {

            $firstDay = date('Y', strtotime('last year')).'-01-01';
            $lastDay = date('Y', strtotime('last year')).'-12-31';
            $saleJobs = $this->__saleByServiceTechQuery($firstDay,$lastDay);
            
        } elseif($request->report_of == config("constant.report_of.last_twelve_month")) {
            
            $firstDay = Carbon::now()->startOfMonth()->subMonth(12);
            $lastDay = Carbon::now()->toDateString();
            $saleJobs = $this->__saleByServiceTechQuery($firstDay,$lastDay);
            
        } else {
            
            $firstDay = $request->start_date;
            $lastDay = $request->end_date;
            $saleJobs = $this->__saleByServiceTechQuery($firstDay,$lastDay);
        }
        
        return response()->json([
            'status' => true,
            'saleJobs' => $saleJobs
        ], 200);
       
    }
    
    private function __saleByServiceTechQuery($firstDay,$lastDay)
    {
        $data = User::whereHas('truckDriverJobs', function($q) use($firstDay, $lastDay) {
                    $q->whereBetween('created_at', [$firstDay, $lastDay]);
                })->orWhereHas('skidsteerDriverJobs', function($q) use($firstDay, $lastDay) {
                    $q->whereBetween('created_at', [$firstDay, $lastDay]);
                })->where('id', 103)->select('id', 'first_name', 'last_name', 'created_at')->with(['truckDriverJobs' => function($q) {
                        $q->select('id', 'customer_id', 'service_id', 'job_providing_date', 'job_providing_time', 'amount', 'job_status', 'payment_status', 'quick_book', 'truck_driver_id', 'skidsteer_driver_id', 'created_at')->with(['customer' => function($q) {
                                $q->select('id', 'first_name', 'last_name');
                            }, 'service' => function($query) {
                                $query->select('id', 'service_name');
                            }]);
                    }, 'skidsteerDriverJobs' => function($q) {
                        $q->select('id', 'customer_id', 'service_id', 'job_providing_date', 'job_providing_time', 'amount', 'job_status', 'payment_status', 'quick_book', 'truck_driver_id', 'skidsteer_driver_id', 'created_at')->with(['customer' => function($q) {
                                $q->select('id', 'first_name', 'last_name');
                            }, 'service' => function($query) {
                                $query->select('id', 'service_name');
                            }]);
                    }
                ])->withCount(['truckDriverJobs', 'truckDriverJobs as truck_driver_jobs_sum'=>function($w){
                $w->select(\DB::raw("SUM(amount) as sum"));
            }, 'skidsteerDriverJobs', 'skidsteerDriverJobs as skidsteer_driver_jobs_sum'=>function($w){
                $w->select(\DB::raw("SUM(amount) as sum"));
            }])->get();
                
            return $data;
    }
    
    
    public function transactionsByCustomer(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'report_of' => 'required',
            'start_date' => 'required_if:report_of,==,'.config("constant.report_of.custom"),
            'end_date' => 'required_if:report_of,==,'.config("constant.report_of.custom")
            ]);
            if ($validator->fails()) {
                return response()->json([
                            'status' => false,
                            'message' => $validator->errors(),
                            'data' => []
                                ], 422);
            }
        
        
        if($request->report_of == config("constant.report_of.this_week")) {
            
            $firstDay = Carbon::now()->startOfWeek()->toDateString();
            $lastDay = Carbon::now()->toDateString();
            $customerTransactions = $this->__transactionByCustomerQuery($firstDay,$lastDay);
            
        } else if($request->report_of == config("constant.report_of.this_month")) {
            
            $firstDay = Carbon::now()->startOfMonth()->toDateString();
            $lastDay = Carbon::now()->toDateString();
            $customerTransactions = $this->__transactionByCustomerQuery($firstDay,$lastDay);
            
        } elseif($request->report_of == config("constant.report_of.last_month")) {
            
            $firstDay = Carbon::now()->startOfMonth()->subMonth()->toDateString();
            $lastDay = Carbon::now()->subMonth()->endOfMonth()->toDateString();
            $customerTransactions = $this->__transactionByCustomerQuery($firstDay,$lastDay);
            
        } elseif($request->report_of == config("constant.report_of.this_year")) {
            
            $firstDay = Carbon::now()->year.'-01-01';
            $lastDay = Carbon::now()->toDateString();
            $customerTransactions = $this->__transactionByCustomerQuery($firstDay,$lastDay);
                
            
        } elseif($request->report_of == config("constant.report_of.last_year")) {

            $firstDay = date('Y', strtotime('last year')).'-01-01';
            $lastDay = date('Y', strtotime('last year')).'-12-31';
            $customerTransactions = $this->__transactionByCustomerQuery($firstDay,$lastDay);
            
        } elseif($request->report_of == config("constant.report_of.last_twelve_month")) {
            
            $firstDay = Carbon::now()->startOfMonth()->subMonth(12);
            $lastDay = Carbon::now()->toDateString();
            $customerTransactions = $this->__transactionByCustomerQuery($firstDay,$lastDay);
            
        } else {
            
            $firstDay = $request->start_date;
            $lastDay = $request->end_date;
            $customerTransactions = $this->__transactionByCustomerQuery($firstDay,$lastDay);
        }
        
        return response()->json([
            'status' => true,
            'customerTransactions' => $customerTransactions
        ], 200);
    }
    
    private function __transactionByCustomerQuery($firstDay,$lastDay)
    {
        
        $customerTransactions = User::whereHas('jobs', function($q) use($firstDay, $lastDay) {
            $q->whereBetween('created_at', [$firstDay, $lastDay]);
        })->select('id', 'first_name', 'last_name', 'created_at')
        ->with(['jobs' => function($query) {
            $query->select('id', 'customer_id', 'job_providing_date', 'job_providing_time', 'amount', 'job_status', 'payment_status', 'payment_mode', 'card_id', 'created_at')->with(['cardDetail' => function($q) {
                    $q->select('id', 'card_number', 'card_brand');
                }]);
            }])
        ->withCount(['jobs', 'jobs as sum' => function($w) {
            $w->select(\DB::raw("SUM(amount) as sum"));
        }])->get();

        return $customerTransactions;
    }
    
    public function transactionsByJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_of' => 'required',
            'start_date' => 'required_if:report_of,==,'.config("constant.report_of.custom"),
            'end_date' => 'required_if:report_of,==,'.config("constant.report_of.custom")
            ]);
            if ($validator->fails()) {
                return response()->json([
                            'status' => false,
                            'message' => $validator->errors(),
                            'data' => []
                                ], 422);
            }
        
        
        if($request->report_of == config("constant.report_of.this_week")) {
            
            $firstDay = Carbon::now()->startOfWeek()->toDateString();
            $lastDay = Carbon::now()->toDateString();
            $jobTransactions = $this->__transactionByJobQuery($firstDay,$lastDay);
            
        } else if($request->report_of == config("constant.report_of.this_month")) {
            
            $firstDay = Carbon::now()->startOfMonth()->toDateString();
            $lastDay = Carbon::now()->toDateString();
            $jobTransactions = $this->__transactionByJobQuery($firstDay,$lastDay);
            
        } elseif($request->report_of == config("constant.report_of.last_month")) {
            
            $firstDay = Carbon::now()->startOfMonth()->subMonth()->toDateString();
            $lastDay = Carbon::now()->subMonth()->endOfMonth()->toDateString();
            $jobTransactions = $this->__transactionByJobQuery($firstDay,$lastDay);
            
        } elseif($request->report_of == config("constant.report_of.this_year")) {
            
            $firstDay = Carbon::now()->year.'-01-01';
            $lastDay = Carbon::now()->toDateString();
            $jobTransactions = $this->__transactionByJobQuery($firstDay,$lastDay);
                
            
        } elseif($request->report_of == config("constant.report_of.last_year")) {

            $firstDay = date('Y', strtotime('last year')).'-01-01';
            $lastDay = date('Y', strtotime('last year')).'-12-31';
            $jobTransactions = $this->__transactionByJobQuery($firstDay,$lastDay);
            
        } elseif($request->report_of == config("constant.report_of.last_twelve_month")) {
            
            $firstDay = Carbon::now()->startOfMonth()->subMonth(12);
            $lastDay = Carbon::now()->toDateString();
            $jobTransactions = $this->__transactionByJobQuery($firstDay,$lastDay);
            
        } else {
            
            $firstDay = $request->start_date;
            $lastDay = $request->end_date;
            $jobTransactions = $this->__transactionByJobQuery($firstDay,$lastDay);
        }

        return response()->json([
            'status' => true,
            'jobTransactions' => $jobTransactions
        ], 200);
    }
    
    private function __transactionByJobQuery($firstDay,$lastDay)
    {
        $jobTransactions = Job::whereBetween('created_at', [$firstDay, $lastDay])
        ->whereHas('customer')
        ->select('id', 'customer_id', 'job_providing_date', 'job_providing_time', 'amount', 'job_status', 'payment_status', 'payment_mode', 'card_id', 'created_at')
        ->with(['customer' => function($query) {
                $query->select('id', 'first_name', 'last_name');
            }, 'cardDetail' => function($q) {
                $q->select('id', 'card_number', 'card_brand');
            }
        ])->get();

        return $jobTransactions;
    }
    
    public function customerList(Request $request)
    {
        if ($request->has('start_date') && $request->has('end_date')) {
            $customersList = User::where('role_id', config('constant.roles.Customer'))
                            ->whereBetween('created_at', [$request->start_date, $request->end_date])->get();

            return response()->json([
                'status' => true,
                'customersList' => $customersList
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Dates are not sent properly.'
        ], 500);
    }
    
    public function customerFarms(Request $request) {
        if ($request->has('start_date') && $request->has('end_date')) {
            $customersFarmList = User::whereHas('farmlist', function($q) use($request) {
                $q->whereBetween('created_at', [$request->start_date, $request->end_date]);
            })->with('farmlist')->get();

            return response()->json([
                        'status' => true,
                        'customersFarmList' => $customersFarmList
                            ], 200);
        }

        return response()->json([
                    'status' => false,
                    'message' => 'Dates are not sent properly.'
                        ], 500);
    }

    // public function getCustomerReport(Request $request)
    // {
    //     //current month
    //     $firstDayofThisMonth = Carbon::now()->startOfMonth()->toDateString();
    //     $currentDayofThisMonth = Carbon::now()->toDateString();
        
    //     //previous month
    //     $firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
    //     $lastDayofPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();
        
    //     if($request->report_of == config("constant.report_of.this_month")) {
    //         $customerFarms = CustomerFarm::whereBetween('created_at', [$firstDayofThisMonth,$currentDayofThisMonth])->get();
    //     } else if($request->report_of == config("constant.report_of.last_month")) {


    //         // $customerFarms = Job::whereHas('farm',function($query) {
    //         //     $query->whereBetween('created_at', [$firstDayofPreviousMonth,$lastDayofPreviousMonth]);
    //         // })->get();

    //         $customerFarms = CustomerFarm::whereBetween('created_at', [$firstDayofPreviousMonth,$lastDayofPreviousMonth])->get();


    //     } else if($request->report_of == config("constant.report_of.this_year")) {
    //         $customerFarms = CustomerFarm::whereBetween('created_at', [Carbon::now()->year.'-01-01',$currentDayofThisMonth])->get();
    //     } else if($request->report_of == config("constant.report_of.last_year")) {
    //         $customerFarms = CustomerFarm::whereBetween('created_at', [date('Y', strtotime('last year')).'-01-01',date('Y', strtotime('last year')).'-12-31'])->get();
    //     } else if($request->report_of == config("constant.report_of.custom")) {
    //         $customerFarms = CustomerFarm::whereBetween('created_at', [$request->start_date,$request->end_date])->where('job_open')->get();
    //     }
    //     $now = date("Ymdhis");
    //     $pdf = PDF::loadView("pdf.customerFarms", compact('customerFarms'))->save(public_path().'/downloads/'.$now.'.pdf');
        
    //     $path = public_path() . '/downloads';
    //     if (!file_exists($path)) {
    //         mkdir($path, 0777, true);
    //     }
        
    //     $headers = [
    //         'Content-Type' => 'application/pdf',
    //     ];
        
    //     return response()->json([
    //         'status' => true,
    //         'name' => "downloads/$now.pdf",
    //         'headers' => $headers
    //     ], 200);
        
    //     return response()->download($name, $headers);
        
    //     dump($customerFarms);
    //     dd($request->all());
        
    // }
    
    private function __jobActivityReportQuery($firstDay,$lastDay)
    {
        $jobActivities = job::whereHas('customer')
        ->whereBetween('created_at', [$firstDay,$lastDay])
        ->select('id', 'customer_id', 'service_id', 'job_providing_date', 'job_providing_time', 'job_status',  'quick_book', 'truck_driver_id', 'skidsteer_driver_id','created_at')
        ->with(['customer'=>function($q){
            $q->select('id','first_name','last_name');
        },'service'=>function($query){
            $query->select('id','service_name','description');
        },'truck_driver' => function($q) {
            $q->select('id', 'first_name', 'last_name');
        }, 'skidsteer_driver' => function($q) {
            $q->select('id', 'first_name', 'last_name');
        }])->get();

        return $jobActivities;
    }
    public function jobActivityReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_of' => 'required',
            'start_date' => 'required_if:report_of,==,'.config("constant.report_of.custom"),
            'end_date' => 'required_if:report_of,==,'.config("constant.report_of.custom")
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
                'data' => []
            ], 422);
        }

        if($request->report_of == config("constant.report_of.this_month")) {
            $firstDay = Carbon::now()->startOfMonth()->toDateString();
            $lastDay = Carbon::now()->toDateString();
            $jobTransactions = $this->__jobActivityReportQuery($firstDay,$lastDay);
            
        } else if($request->report_of == config("constant.report_of.last_month")) {
            $firstDay = Carbon::now()->startOfMonth()->subMonth()->toDateString();
            $lastDay = Carbon::now()->subMonth()->endOfMonth()->toDateString();
            $jobTransactions = $this->__jobActivityReportQuery($firstDay,$lastDay);

        } else if($request->report_of == config("constant.report_of.last_week")) {
            $firstDay = Carbon::now()->startOfWeek()->subWeek()->format('Y-m-d');
            $lastDay = Carbon::now()->endOfWeek()->subWeek()->format('Y-m-d');
            $jobTransactions = $this->__jobActivityReportQuery($firstDay,$lastDay);
            
        } else if($request->report_of == config("constant.report_of.this_week")) {
            $firstDay = Carbon::now()->startOfWeek()->format('Y-m-d');
            $lastDay = Carbon::now()->endOfWeek()->format('Y-m-d');
            $jobTransactions = $this->__jobActivityReportQuery($firstDay,$lastDay);
            
        } else {
            
            $jobTransactions = $this->__jobActivityReportQuery($request->start_date,$request->end_date);
        
        }

        return response()->json([
            'status' => true,
            'jobTransactions' => $jobTransactions
        ], 200);
    }
    
    private function __workedDriverHoursQuery($firstDay,$lastDay)
    {
        $workedHours = User::where('role_id',config('constant.roles.Driver'))
        ->orWhereHas('truckDriverJobs',function($query) use($firstDay,$lastDay) {
            $query->whereBetween('created_at', [$firstDay,$lastDay]);
        })
        ->orWhereHas('skidsteerDriverJobs',function($query) use($firstDay,$lastDay) {
            $query->whereBetween('created_at', [$firstDay,$lastDay]);
        })
        ->with(['driver'=>function($query){
            $query->select('id','user_id','salary_type','driver_salary');
        },'truckDriverJobs'=>function($query) use($firstDay,$lastDay) {
            $query->select('id','truck_driver_id','start_time','end_time','is_repeating_job','created_at','amount')
            ->whereBetween('created_at', [$firstDay,$lastDay])
            ->with(['jobAssignmentHistories'=>function($query){
                $query->select('id','job_id','start_time','end_time');
            }]);
        },'skidsteerDriverJobs'=>function($query) use($firstDay,$lastDay) {
            $query->select('id','truck_driver_id','start_time','end_time','is_repeating_job','created_at','amount')
            ->whereBetween('created_at', [$firstDay,$lastDay])
            ->with(['jobAssignmentHistories'=>function($query){
                $query->select('id','job_id','start_time','end_time');
            }]);
        }])
        ->select('id','first_name','last_name','created_at')
        ->get();

        $a=[];
        
        // $total1 = Carbon::setTime(0, 0, 0);
        $workedHours['total_drivers'] = count($workedHours);
        foreach($workedHours as $hour) {
            if(isset($total) && !empty($total)){
                $total = Carbon::today()->startOfDay()->format("H:i:s");
            }
            if(isset($total1) && !empty($total1)){
                $total1 = Carbon::today()->startOfDay()->format("H:i:s");
            }
            if(isset($total_amount) && !empty($total_amount)){
                $total_amount = 0;
            }
            $total_amount = 0;
            $total = Carbon::today()->startOfDay()->format("H:i:s");
            $total1 = Carbon::today()->startOfDay()->format("H:i:s");
            if(isset($hour['truckDriverJobs'])&& !empty($hour['truckDriverJobs'])){
                
                foreach($hour['truckDriverJobs'] as $job) {
                    if(!empty($job['end_time']) && !empty($job['start_time'])) {
                        
                        $end_time = Carbon::parse($job['end_time']);
                        $start_time = Carbon::parse($job['start_time']);
                        
                        $job['time_diff'] = gmdate("H:i:s",$end_time->diffInSeconds($start_time));
                        
                        $times = explode(':', $job['time_diff']);
                        $hours = $times[0];
                        $minutes = $times[1];
                        $seconds = $times[2];
                        
                        $total = Carbon::parse($total)
                        ->copy()
                        ->addHours($hours)
                        ->addMinutes($minutes)
                        ->addSeconds($seconds);
                    }
                    $total_amount = $total_amount + $job['amount'];
                }
                $hour['total_time_truck'] = Carbon::parse($total)->format("H:i:s");
                
                $a = $hour['truckDriverJobs'];
                unset($hour['truckDriverJobs']);
                $hour['truckDriverJobs'] = $a->groupBy("created_at");
                $hour['total_amount'] = $total_amount;
            }
            if(isset($hour['skidsteerDriverJobs'])&& !empty($hour['skidsteerDriverJobs'])){
                foreach($hour['skidsteerDriverJobs'] as $job){
                    if(!empty($job['end_time']) && !empty($job['start_time'])) {
                        
                        $end_time = Carbon::parse($job['end_time']);
                        $start_time = Carbon::parse($job['start_time']);
                        
                        $job['time_diff'] = gmdate("H:i:s",$end_time->diffInSeconds($start_time));
                        
                        $times = explode(':', $job['time_diff']);
                        $hours = $times[0];
                        $minutes = $times[1];
                        $seconds = $times[2];
                        
                        $total1 = Carbon::parse($total1)
                        ->copy()
                        ->addHours($hours)
                        ->addMinutes($minutes)
                        ->addSeconds($seconds);
                    }
                    $total_amount = $total_amount + $job['amount'];
                }
                $hour['total_time_skidsteer'] = Carbon::parse($total1)->format("H:i:s");
                $b = $hour['skidsteerDriverJobs'];
                unset($hour['skidsteerDriverJobs']);
                $hour['skidsteerDriverJobs'] = $b->groupBy("created_at");
                $hour['total_amount'] = $total_amount;
            }
            
        }
        foreach($workedHours as $driver) {
            if(isset($driver['driver']) && !empty($driver['driver'])){
                if($driver['driver']['salary_type'] == config('constant.salary_type.per_hour')){

                    $times = explode(':', $driver['total_time_truck']);
                    $hours = $times[0];
                    $minutes = $times[1];
                    $seconds = $times[2];

                    $total = Carbon::parse($driver['total_time_skidsteer'])
                        ->copy()
                        ->addHours($hours)
                        ->addMinutes($minutes)
                        ->addSeconds($seconds);

                    $a = Carbon::parse($total)->format("H:i:s");

                    $totalExplode = explode(':', $a);

                    $worktime = $totalExplode[0] * 60 + $totalExplode[1];
                    $amountInCents = $worktime * $driver['driver']['driver_salary']; 
                    
                    $driver['calculated_salary'] = number_format(($amountInCents /100), 2, '.', ' ');
                     
                    // $calculatedSalary = $driver['driver']['driver_salary'];
                } else {
                    $driver['calculated_salary'] = $driver['driver']['driver_salary'];
                }
            }
        }

        return $workedHours;
    }
    public function workedDriverHours(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_of' => 'required',
            'start_date' => 'required_if:report_of,==,'.config("constant.report_of.custom"),
            'end_date' => 'required_if:report_of,==,'.config("constant.report_of.custom")
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
                'data' => []
            ], 422);
        }
        
        if($request->report_of == config("constant.report_of.last_two_week")) {
            
            $firstDay = Carbon::now()->startOfWeek()->subWeek(2)->format('Y-m-d');
            $lastDay = Carbon::now()->endOfWeek()->subWeek(1)->format('Y-m-d');
            $jobTransactions = $this->__workedDriverHoursQuery($firstDay,$lastDay);
            
        } else if($request->report_of == config("constant.report_of.last_week")) {
            $firstDay = Carbon::now()->startOfWeek()->subWeek()->format('Y-m-d');
            $lastDay = Carbon::now()->endOfWeek()->subWeek()->format('Y-m-d');
            $jobTransactions = $this->__workedDriverHoursQuery($firstDay,$lastDay);
            
        } else if($request->report_of == config("constant.report_of.this_week")) {
            $firstDay = Carbon::now()->startOfWeek()->format('Y-m-d');
            $lastDay = Carbon::now()->endOfWeek()->format('Y-m-d');
            $jobTransactions = $this->__workedDriverHoursQuery($firstDay,$lastDay);
            
        } else {
            
            $jobTransactions = $this->__workedDriverHoursQuery($request->start_date,$request->end_date);
            
        }
        
        return response()->json([
            'status' => true,
            'jobTransactions' => $jobTransactions
        ], 200);
    }
    
    private function __jobDriveAndLabourTimesQuery($firstDay,$lastDay)
    {
        $driveAndLabourTimes = User::where('role_id',config('constant.roles.Driver'))
        ->whereHas('truckDriverJobs',function($query) use($firstDay,$lastDay) {
            $query->whereBetween('created_at', [$firstDay,$lastDay]);
        })
        ->orWhereHas('skidsteerDriverJobs',function($query) use($firstDay,$lastDay) {
            $query->whereBetween('created_at', [$firstDay,$lastDay]);
        })
        ->with(['driver'=>function($query){
            $query->select('id','user_id','salary_type','driver_salary');
            },'truckDriverJobs'=>function($query) use($firstDay,$lastDay) {
            $query->select('id','farm_id','service_id','truck_driver_id','job_providing_date','start_time','end_time','created_at','is_repeating_job')
            ->whereBetween('created_at', [$firstDay,$lastDay])
            ->with(['farm'=>function($query){
                $query->select('id','farm_city','farm_zipcode','latitude','longitude');
            },'service'=>function($query){
                $query->select('id','service_name');
            },'jobAssignmentHistories'=>function($query){
                $query->select('id','job_id','start_time','end_time');
            }]);
        },'skidsteerDriverJobs'=>function($query) use($firstDay,$lastDay) {
            $query->select('id','farm_id','service_id','truck_driver_id','job_providing_date','start_time','end_time','created_at','is_repeating_job')
            ->whereBetween('created_at', [$firstDay,$lastDay])
            ->with(['farm'=>function($query){
                $query->select('id','farm_city');
            },'service'=>function($query){
                $query->select('id','service_name');
            },'jobAssignmentHistories'=>function($query){
                $query->select('id','job_id','start_time','end_time');
            }]);
        }])
        ->select('id','first_name','last_name','created_at')
        ->get();
        if(isset($driveAndLabourTimes) && !empty($driveAndLabourTimes)) {
            foreach($driveAndLabourTimes as $key=>$time) {
                if(isset($allJobs) && !empty($allJobs)){
                    $allJobs = [];
                }
                if(isset($time['truckDriverJobs']) && !empty($time['truckDriverJobs'])) {
                    
                    $allJobs = [];
                    foreach($time['truckDriverJobs'] as $key1=>$val) {
                        
                        $allJobs[] = $val;
                        if(isset($val['jobAssignmentHistories']) && !empty($val['jobAssignmentHistories'])) {
                            foreach($val['jobAssignmentHistories'] as $job1) {
                                $allJobs[] = $job1;
                            }
                        }
                        
                        unset($time['truckDriverJobs']);
                    }
                }
                
                if(isset($time['skidsteerDriverJobs']) && !empty($time['skidsteerDriverJobs'])) {
                    
                    foreach($time['skidsteerDriverJobs'] as $key1=>$val) {
                        
                        $allJobs[] = $val;
                        if(isset($val['jobAssignmentHistories']) && !empty($val['jobAssignmentHistories'])) {
                            foreach($val['jobAssignmentHistories'] as $job1) {
                                $allJobs[] = $job1;
                            }
                        }
                        
                        unset($time['skidsteerDriverJobs']);
                    }
                }
                $time['jobs'] = $allJobs;
            }

        }

        return $driveAndLabourTimes;
    }

    public function jobDriveAndLabourTimes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_of' => 'required',
            'start_date' => 'required_if:report_of,==,'.config("constant.report_of.custom"),
            'end_date' => 'required_if:report_of,==,'.config("constant.report_of.custom")
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => []
            ], 422);
        }

        if($request->report_of == config("constant.report_of.last_two_week")) {

            $firstDay = Carbon::now()->startOfWeek()->subWeek(2)->format('Y-m-d');
            $lastDay = Carbon::now()->endOfWeek()->subWeek(1)->format('Y-m-d');
            $driveAndLabourTimes = $this->__jobDriveAndLabourTimesQuery($firstDay,$lastDay);
            
        } else if($request->report_of == config("constant.report_of.last_week")) {
            $firstDay = Carbon::now()->startOfWeek()->subWeek()->format('Y-m-d');
            $lastDay = Carbon::now()->endOfWeek()->subWeek()->format('Y-m-d');
            $driveAndLabourTimes = $this->__jobDriveAndLabourTimesQuery($firstDay,$lastDay);
            
        } else if($request->report_of == config("constant.report_of.this_week")) {
            $firstDay = Carbon::now()->startOfWeek()->format('Y-m-d');
            $lastDay = Carbon::now()->endOfWeek()->format('Y-m-d');
            $driveAndLabourTimes = $this->__jobDriveAndLabourTimesQuery($firstDay,$lastDay);
            
        } else {
            
            $driveAndLabourTimes = $this->__jobDriveAndLabourTimesQuery($request->start_date,$request->end_date);
        
        }

        return response()->json([
            'status' => true,
            'driveAndLabourTimes' => $driveAndLabourTimes
        ], 200);
    }
    
}
