<?php

class LocationsSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		foreach (Config::get('buildings') as $building)
		{
			Building::create($building);
		}
	}

}