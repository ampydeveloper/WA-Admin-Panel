<?php

namespace App\Console\Commands;

use App\Http\Controllers\CronController;
use App\Http\Controllers\SalaryController;
use Illuminate\Console\Command;
//use App\Models\District;

class CalculateSalary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:salary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate salary on monthly basis for truck driver';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $calculateSalary;
    
    public function __construct(SalaryController $calculateSalary)
    {
        parent::__construct();
        $this->calculateSalary = $calculateSalary;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->calculateSalary->calculateSalary();
    }
}
