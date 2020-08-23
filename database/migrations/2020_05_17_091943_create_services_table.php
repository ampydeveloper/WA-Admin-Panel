<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            
            $table->string("service_name");
            $table->float("price");
            $table->string("description", 1000);
            $table->tinyInteger("service_type")->comment("1: by weight, 2: by round");
            $table->string("service_image");
            $table->tinyInteger("service_for")->comment("4: for customer, 6: for haulers");
            $table->json("slot_type")->nullable();
            $table->json("slot_time")->nullable();
            $table->unsignedBigInteger('service_created_by');
            $table->foreign('service_created_by')->references('id')->on('users');
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
        Schema::dropIfExists('services');
      
    }
}
