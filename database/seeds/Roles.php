<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'role_name' => 'Admin',
            'created_at' => Carbon::now()
        ]);
        DB::table('roles')->insert([
            'role_name' => 'Admin Manager',
            'created_at' => Carbon::now()
        ]);
        DB::table('roles')->insert([
            'role_name' => 'Truck Driver',
            'created_at' => Carbon::now()
        ]);
        DB::table('roles')->insert([
            'role_name' => 'Customer',
            'created_at' => Carbon::now()
        ]);
        DB::table('roles')->insert([
            'role_name' => 'Customer Manager',
            'created_at' => Carbon::now()
        ]);
        DB::table('roles')->insert([
            'role_name' => 'Company',
            'created_at' => Carbon::now()
        ]);
    }
}
