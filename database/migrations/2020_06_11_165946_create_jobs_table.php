<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('users');
            
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->foreign('manager_id')->references('id')->on('users');
            
            $table->unsignedBigInteger('farm_id')->nullable();
            $table->foreign('farm_id')->references('id')->on('customer_farms');
            
            $table->string("gate_no")->nullable();
            
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services');
            
            $table->unsignedBigInteger('time_slots_id')->nullable();
            $table->foreign('time_slots_id')->references('id')->on('time_slots');
            
            $table->date('job_providing_date');
            
            $table->float("weight")->nullable();
            
            $table->tinyInteger("is_repeating_job")->default(0)->comment("0: false, 1:true");
            
            $table->json("repeating_days")->nullable();
            
            $table->json("images")->nullable();
            
            $table->longText('notes')->nullable();
            
            $table->float("amount");
            
            $table->tinyInteger("payment_mode")->default(0)->comment("0: online, 1: cheque, 2: cash");
            
            $table->tinyInteger("job_status")->default(0)->comment("0: open, 1: assigned, 2: completed, 3:close, 4: cancelled");
            
            $table->tinyInteger("payment_status")->default(0)->comment("0: unpaid, 1:paid");
            
            $table->tinyInteger("quick_book")->default(0)->comment("0:Not Sync, 1: Sync");
            
            $table->unsignedBigInteger('truck_id')->nullable();
            $table->foreign('truck_id')->references('id')->on('vehicles');
            
            $table->unsignedBigInteger('truck_driver_id')->nullable();
            $table->foreign('truck_driver_id')->references('id')->on('users');
            
            $table->unsignedBigInteger('skidsteer_id')->nullable();
            $table->foreign('skidsteer_id')->references('id')->on('vehicles');
            
            $table->unsignedBigInteger('skidsteer_driver_id')->nullable();
            $table->foreign('skidsteer_driver_id')->references('id')->on('users');
            
            $table->time('start_time')->nullable();

            $table->time('end_time')->nullable();

            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('jobs');
    }
}
