<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuildingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('buildings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('short_name');
			$table->string('picture');
			$table->string('address');
			$table->double('lat');
			$table->double('lng');
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
		Schema::dropIfExists('buildings');
	}

}
