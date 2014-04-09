<?php

class LocationsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$buildings = array_map(function(array $x) {
			return (object) $x;
		}, Config::get('buildings'));

		$box = ['top' => $buildings[0]->lat, 'right' => $buildings[0]->lng, 'bottom' => $buildings[0]->lat, 'left' => $buildings[0]->lng];

		// Sort buildings by name
		usort($buildings, function($a, $b){
			return strcasecmp($a->name, $b->name);
		});

		foreach ($buildings as $building)
		{
			if ($box['left'] > $building->lng)
			{
				$box['left'] = $building->lng;
			}
			if ($box['right'] < $building->lng)
			{
				$box['right'] = $building->lng;
			}
			if ($box['top'] < $building->lat)
			{
				$box['top'] = $building->lat;
			}
			if ($box['bottom'] > $building->lat)
			{
				$box['bottom'] = $building->lat;
			}
		}

		$this->layout->with(['box' => $box, 'buildings' => $buildings]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}