<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrucksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger("vehicle_type")->comment("1: Truck, 2: JCB");
            $table->string("company_name");
            $table->string("truck_number");
            $table->string("chaase_number");
            $table->string("killometer");
            $table->string("capacity")->nullable();
            $table->string("document");
            $table->tinyInteger("status")->comment("true: Available, false: Unavailable")->nullable();
            $table->timestamp("assigned_job_date_time")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
