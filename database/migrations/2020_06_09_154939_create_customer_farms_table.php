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

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('users');

            $table->string('farm_address')->nullable();
            $table->string('farm_city')->nullable();
            $table->json('farm_image')->nullable();
            $table->string('farm_province')->nullable();
            $table->string('farm_unit')->nullable();
            $table->string('farm_zipcode')->nullable();
            $table->tinyInteger('farm_active')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

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
