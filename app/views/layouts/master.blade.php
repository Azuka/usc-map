<!DOCTYPE html>
<html>
<head>
	<!-- Standard Meta -->
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<!-- Site Properities -->
	<title>USC Buildings</title>

	<link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:400,700|Open+Sans:300italic,400,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="/css/semantic.css">

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.address/1.6/jquery.address.js"></script>
	<link rel="stylesheet" href="//cdn.leafletjs.com/leaflet-0.7.2/leaflet.css" />
	<script src="//cdn.leafletjs.com/leaflet-0.7.2/leaflet.js"></script>
	<script src="/javascript/semantic.js"></script>

	<link rel="stylesheet" type="text/css" href="/css/feed.css">
	<script src="/javascript/feed.js"></script>
</head>
<body id="feed">
<div class="ui large inverted vertical sidebar menu">
	<div class="item">
		<div class="ui form">
			<div class="field">
				<div class="ui icon input">
					<i class="search icon"></i>
					<input type="text" data-placeholder="Name">
				</div>
			</div>
		</div>
	</div>
</div>
<div class="ui celled grid">
	<div class="four wide middle column">
		<div class="ui right floated black launch button">
			<i class="list layout icon"></i> Search
		</div>
		<h2 class="ui header">
			<i class="map marker icon"></i>
			USC Buildings
		</h2>
		<div class="ui tabular filter menu">
			<a class="active item" data-tab="upc">UPC</a>
			<a class="item" data-tab="hsc">HSC</a>
			<a class="item" data-tab="all">All</a>
		</div>
		<div class="ui divided inbox selection list active tab" data-tab="upc">
			@foreach ($buildings as $id => $building)
			@if (strpos($building->address, '90089') !== false)
			<a class="item" data-title="{{{ $building->address }}}" data-search="{{{ $building->name }}} {{{ $building->short_name }}} {{{ $building->address }}}" data-id="{{{ $id }}}">
				<div class="left floated ui star rating"><i class="icon"></i></div>
				<div class="right floated date">{{{ $building->short_name }}}</div>
				<div class="description">{{{ $building->name }}}</div>
			</a>
			@endif
			@endforeach
		</div>
		<div class="ui divided inbox selection list tab" data-tab="hsc">
			@foreach ($buildings as $id => $building)
			@if (strpos($building->address, '90089') === false)
			<a class="item" data-title="{{{ $building->address }}}" data-search="{{{ $building->name }}} {{{ $building->short_name }}} {{{ $building->address }}}" data-id="{{{ $id }}}">
				<div class="left floated ui star rating"><i class="icon"></i></div>
				<div class="right floated date">{{{ $building->short_name }}}</div>
				<div class="description">{{{ $building->name }}}</div>
			</a>
			@endif
			@endforeach
		</div>
		<div class="ui divided inbox selection list tab" data-tab="all">
			@foreach ($buildings as $id => $building)
			<a class="item" data-title="{{{ $building->address }}}" data-search="{{{ $building->name }}} {{{ $building->short_name }}} {{{ $building->address }}}" data-id="{{{ $id }}}">
				<div class="left floated ui star rating"><i class="icon"></i></div>
				<div class="right floated date">{{{ $building->short_name }}}</div>
				<div class="description">{{{ $building->name }}}</div>
			</a>
			@endforeach
		</div>

		<div class="ui divider"></div>

		<div class="page">Showing <b>6</b> of 213</div>
		<div class="ui pagination menu">
			<a class="icon item"><i class="icon left arrow"></i></a>
			<a class="active item">1</a>
			<div class="disabled item">...</div>
			<a class="item">10</a>
			<a class="item">11</a>
			<a class="item">12</a>
			<a class="icon item"><i class="icon right arrow"></i></a>
		</div>
	</div>
	<div class="twelve wide right column">
		<div id="map"></div>
	</div>
</div>
<script>

$(function(){
	var map = L.map('map', {
		center: [51.505, -0.09],
		zoom: 13
	});

	var buildings = {{json_encode($buildings)}};
	var i;

	var osmTile = "http://tile.openstreetmap.org/{z}/{x}/{y}.png",
	osmCopyright = "Map data &copy; 2012 OpenStreetMap contributors",
	osmLayer = new L.TileLayer(osmTile, { maxZoom: 18, attribution: osmCopyright } );
	map.addLayer( osmLayer );
	var markers = new Array();
	var popups = new Array();

	var southWest = L.latLng({{$box['bottom']}}, {{$box['left']}}),
		northEast = L.latLng({{$box['top']}}, {{$box['right']}});

	var marker;

	map.fitBounds(L.latLngBounds(southWest, northEast));

	for (i = 0; i < buildings.length; i++)
	{
		marker = L.marker( L.latLng(buildings[i].lat, buildings[i].lng), {title: buildings[i].name, mid: i});
		marker.bindPopup('<strong>'+buildings[i].name+'</strong><br>'+buildings[i].short_name+'<p>'+buildings[i].address+'</p>').openPopup();

		marker.on('mouseover', function(e){
			console.log('.item[data-id='+this.mid+']');
			console.log(this);
			console.log(e);
			$('.item[data-id='+ this.options.mid+']', '.four.wide').addClass('active');
			this.openPopup();
		});
		marker.on('mouseout', function(e){
			$('.item[data-id='+ this.options.mid+']', '.four.wide').removeClass('active');
		});

		markers.push(marker.addTo(map));
	}

	$('.four.wide .item[data-id]').on('click', function() {
		markers[$(this).data('id')].openPopup();
	});
});
</script>
</body>

</html>