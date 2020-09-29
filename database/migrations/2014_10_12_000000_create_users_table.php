<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('prefix', 50)->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string("phone")->nullable();
            $table->string("address")->nullable();
            $table->string("city")->nullable();
            $table->string("state")->nullable();
            $table->string("zip_code")->nullable();
            $table->string("user_image")->nullable();
            $table->integer("payment_mode")->nullable()->comment('0: online, 1: cheque');
            $table->integer('role_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string("hauler_driver_licence")->nullable();
            $table->string("hauler_driver_licence_image")->nullable();
            $table->string("user_image")->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullable();
            $table->unsignedBigInteger('created_from_id');
            $table->foreign('created_from_id')->references('id')->on('users');
            $table->tinyInteger('is_confirmed')->default(0);
            $table->tinyInteger('is_active')->default(0);
            $table->string("provider")->nullable();
            $table->string("token")->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->dateTime("password_changed_at")->after("password")->nullable();
            $table->rememberToken();
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
       Schema::dropIfExists('users');
    }
}
