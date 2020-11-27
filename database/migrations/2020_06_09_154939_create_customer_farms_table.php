<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerFarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_farms', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('users');
            $table->string('farm_address');
            $table->string('farm_unit')->nullable();
            $table->string('farm_city');
            $table->string('farm_province');
            $table->string('farm_zipcode');
            $table->json('farm_image')->nullable();
            $table->tinyInteger('farm_active');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('distance_warehouse');
            $table->string('distance_dumping_area');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('customer_farms');
    }
}
