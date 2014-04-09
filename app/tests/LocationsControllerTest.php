<?php

class LocationsControllerTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testIndex()
	{
		$this->client->request('GET', '/');

		$this->assertTrue($this->client->getResponse()->isOk());
	}

}