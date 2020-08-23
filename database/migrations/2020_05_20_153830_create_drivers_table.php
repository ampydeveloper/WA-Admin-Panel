<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger("driver_type")->after("user_id")->comment("1: Truck Driver, 2: JCB");
            $table->string("driver_licence");
            $table->dateTime("expiry_date");
            $table->string("document");
            $table->tinyInteger("salary_type")->comments("0: Per Hour, 1: Per month");
            $table->float("driver_salary")->after("salary_type");
            $table->tinyInteger("status")->comments("1: available, 2: not available");
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
       Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('drivers');
      
    }
}
