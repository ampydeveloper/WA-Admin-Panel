<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerCardDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_card_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('users');

            $table->string("card_id")->nullable();
            $table->string("card_number")->nullable();
            $table->integer("card_exp_month")->nullable();
            $table->integer("card_exp_year")->nullable();
            $table->tinyInteger("card_status")->nullable()->comment("1: Active, 2: Inactive");
            $table->tinyInteger("card_primary")->nullable()->comment("1: Yes, 2: No");

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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('customer_card_details');
    }
}
