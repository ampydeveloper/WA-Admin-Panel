<?php

use Illuminate\Database\Seeder;
use App\TimeSlots;
class TimeSlotsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $times = array(
	    ['slot_type' => 1,'slot_start' => '7AM','slot_end' => '8AM'], 
	    ['slot_type' => 1,'slot_start' => '8AM','slot_end' => '9AM'], 
	    ['slot_type' => 1,'slot_start' => '9AM','slot_end' => '10AM'], 
	    ['slot_type' => 1,'slot_start' => '10AM','slot_end' => '11AM'], 
	    ['slot_type' => 1,'slot_start' => '11AM','slot_end' => '12PM'], 
	    ['slot_type' => 2,'slot_start' => '12PM','slot_end' => '1PM'], 
	    ['slot_type' => 2,'slot_start' => '1PM','slot_end' => '2PM'], 
            ['slot_type' => 2,'slot_start' => '2PM','slot_end' => '3PM'],
            ['slot_type' => 2,'slot_start' => '3PM','slot_end' => '4PM'],
            ['slot_type' => 2,'slot_start' => '4PM','slot_end' => '5PM'],
            ['slot_type' => 2,'slot_start' => '5PM','slot_end' => '6PM'],
	);

		foreach ($times as $time) {
		  DB::table('time_slots')->insert($time);
		}
    }
}
