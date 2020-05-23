<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //drop columns from vehicles table
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn("insurance_number");
            $table->dropColumn("insurance_date");
            $table->dropColumn("insurance_expiry");

            $table->float("killometer")->after("chaase_number")->nullable();
            $table->float("capacity")->after("killometer")->nullable();
        });

        Schema::create('vehicle_services', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');

            $table->dateTime("service_date")->nullable();
            $table->dateTime("service_expiry")->nullable();
            $table->float("service_killometer")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_services');
    }
}
