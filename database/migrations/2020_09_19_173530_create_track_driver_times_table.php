<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackDriverTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_driver_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('stop_time')->nullable();
            $table->integer('total_time_in_second')->default(0);
            $table->integer('day_number')->nullable();
            $table->integer('month_number')->nullable();
            $table->integer('year_yyyy')->nullable();
            $table->integer('is_settled')->default(0);
            $table->integer('row_action_count')->default(1);
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
        Schema::dropIfExists('track_driver_times');
    }
}
