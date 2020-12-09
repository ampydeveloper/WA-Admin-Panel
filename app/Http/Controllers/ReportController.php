<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomerFarm;
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
            $customerFarms = CustomerFarm::whereBetween('created_at', [$request->start_date,$request->end_date])->get();
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
}
