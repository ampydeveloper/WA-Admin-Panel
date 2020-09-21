<?php

namespace App\Console\Commands;

use App\Http\Controllers\CronController;
use Illuminate\Console\Command;
//use App\Models\District;

class AssignTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign task on daily basis to truck';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $assignTask;
    
    public function __construct(CronController $assignTask)
    {
        parent::__construct();
        $this->assignTask = $assignTask;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->assignTask->assignTecs();
    }
}
