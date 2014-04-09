<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		if (file_exists(app_path('database/production.sqlite')))
		{
			unlink(app_path('database/production.sqlite'));
		}

		touch(app_path('database/production.sqlite'));

		Artisan::call('migrate');

		$this->call('LocationsSeeder');
	}

}