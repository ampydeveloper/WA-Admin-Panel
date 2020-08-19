<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrefixToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //add to user table
        Schema::table('users', function (Blueprint $table) {
            $table->string('prefix', 50)->after('last_name')->nullable();

            $table->unsignedBigInteger('created_by')->after('id')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('prefix');
            Schema::disableForeignKeyConstraints();
        });
    }
}
