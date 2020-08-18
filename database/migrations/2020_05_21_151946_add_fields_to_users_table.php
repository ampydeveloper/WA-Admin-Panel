<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //add to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string("address")->after("phone")->nullable();
            $table->string("city")->after("address")->nullable();
            $table->string("state")->after("city")->nullable();
            $table->string("country")->after("state")->nullable();
            $table->string("zip_code")->after("country")->nullable();
        });
        //add to drivers table
        Schema::table('drivers', function (Blueprint $table) {
            $table->tinyInteger("driver_type")->after("user_id")->comment("1: Truck Driver, 2: JCB")->nullable();
            $table->float("driver_salary")->after("salary_type")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //add to users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("address");
            $table->dropColumn("city");
            $table->dropColumn("state");
            $table->dropColumn("country");
        });
        //add to drivers table
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn("driver_type");
            $table->dropColumn("driver_salary");
        });
    }
}
