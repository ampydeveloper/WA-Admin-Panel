<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTimeSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_time_slots', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('services_id');

            $table->foreign('services_id')->references('id')->on('services')->onDelete('cascade');

            $table->tinyInteger("slot_type")->nullable()->comment("1: Morning, 2: Afternoon, 3: Evening");
            $table->dateTime("slot_start")->nullable();
            $table->dateTime("slot_end")->nullable();

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
        Schema::dropIfExists('services_time_slots');
    }
}
