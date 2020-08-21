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
            $table->string('prefix', 50);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string("phone")->nullable();
            $table->string("address")->nullable();
            $table->string("city")->nullable();
            $table->string("state")->nullable();
            $table->string("zip_code")->nullable();
            $table->string("user_image")->nullable();
            $table->integer('role_id')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('created_by')->after('id')->nullable();
            $table->tinyInteger('is_confirmed')->default(0)->nullable();
            $table->tinyInteger('is_active')->default(0)->nullable();
            $table->string("provider")->nullable();
            $table->string("token")->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->dateTime("password_changed_at")->after("password")->nullable();
            $table->rememberToken();
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
       Schema::dropIfExists('users');
    }
}
