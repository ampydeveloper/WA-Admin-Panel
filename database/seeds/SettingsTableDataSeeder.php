<?php

use Illuminate\Database\Seeder;
use App\Settings;
class SettingsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $datas = array(
	    ['type' => 'dumping_area','address' => '14793 50th St S, Wellington, FL 33414','lat' => '26.6094155', 'long' => '-80.2853179']
	);

	foreach ($datas as $data) {
	  DB::table('settings')->insert($data);
	}
    }
}
