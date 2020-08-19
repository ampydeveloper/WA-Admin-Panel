<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJobDateToJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            //add new column
            $table->date('job_date')->nullable()->after("skidsteer_driver_id");
            //remove previous column
            $table->dropColumn('start_date');
            $table->dropColumn('start_time');
            $table->dropColumn('end_date');
            $table->dropColumn('end_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('job_date');

            $table->date('start_date')->nullable()->after("skidsteer_driver_id");
            $table->time('start_time')->nullable()->after("start_date");

            $table->date('end_date')->nullable()->after("start_time");
            $table->time('end_time')->nullable()->after("end_date");
        });
    }
}
