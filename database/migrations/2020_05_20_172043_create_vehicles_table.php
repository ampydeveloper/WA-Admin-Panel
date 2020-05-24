<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
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

            $table->tinyInteger("vehicle_type")->comment("1: Truck, 2: JCB")->nullable();
            $table->string("company_name")->nullable();
            $table->string("truck_number")->nullable();
            $table->string("chaase_number")->nullable();
            $table->string("insurance_number")->nullable();
            $table->dateTime("insurance_date")->nullable();
            $table->dateTime("insurance_expiry")->nullable();
            $table->string("document")->nullable();

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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('vehicles');
        
    }
}
