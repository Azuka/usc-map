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
	<link rel="stylesheet" type="text/css" href="/css/leaflet.awesome-markers.css">
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.address/1.6/jquery.address.js"></script>
	<link rel="stylesheet" href="//cdn.leafletjs.com/leaflet-0.7.2/leaflet.css" />
	<script src="//cdn.leafletjs.com/leaflet-0.7.2/leaflet.js"></script>
	<script src="/javascript/semantic.js"></script>
	<script src="/javascript/leaflet.awesome-markers.min.js"></script>

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
			Buildings
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

	var defaultMarker = L.AwesomeMarkers.icon({
		icon: 'location-arrow',
		markerColor: 'cadetblue',
		prefix: 'fa'
	});
	var bookMarker = L.AwesomeMarkers.icon({
		icon: 'bookmark',
		markerColor: 'red',
		prefix: 'fa'
	});

	for (i = 0; i < buildings.length; i++)
	{
		marker = L.marker( L.latLng(buildings[i].lat, buildings[i].lng), {title: buildings[i].name, mid: i, icon: defaultMarker});
		marker.bindPopup('<strong>'+buildings[i].name+'</strong><br>'+buildings[i].short_name+'<p>'+buildings[i].address+'</p>').openPopup();

		marker.on('mouseover', function(e){
			$('.item[data-id='+ this.options.mid+']', '.four.wide').addClass('active');
			this.openPopup();
		});
		marker.on('mouseout', function(e){
			$('.item[data-id='+ this.options.mid+']', '.four.wide').removeClass('active');
		});

		markers.push(marker.addTo(map));
	}

	var sinput = $('.sidebar input'), keyTimeout, lastFilter, lis = $('.four.wide .item[data-id]');

	sinput.blur(function() {
		$('.ui.sidebar').sidebar('hide');
	}).change(function(e) {
		var numShown = 0;
		$('.four.wide .item[data-id]').each(function(i, li){
			if ($(li).data('search').toLowerCase().indexOf(sinput.val().toLowerCase()) >= 0) {
				$(li).show();
				numShown++;
			} else {
				$(li).hide();
			}
		});
		e.preventDefault();
	}).keydown(function() {
		clearTimeout(keyTimeout);
		keyTimeout = setTimeout(function() {
			if( sinput.val() === lastFilter ) return;
			lastFilter = sinput.val();
			sinput.change();
		}, 0);
	});

	$('.ui.rating').rating({
		clearable: true,
		onRate: function (e)
		{
			var $parent = $(this).closest('.item'),
				$icon   = e == 1 ? bookMarker : defaultMarker;

			markers[$parent.data('id')].setIcon($icon);
		}
	});

	var prevMarker;

	$('.four.wide .item[data-id]').on('click', function() {
		if (prevMarker && (prevMarker !== markers[$(this).data('id')]))
		{
			prevMarker.setZIndexOffset(0);
		}
		markers[$(this).data('id')].setZIndexOffset(1000).openPopup();
		prevMarker = markers[$(this).data('id')];
	}).on('dblclick', function(){
		map.setView(markers[$(this).data('id')].getLatLng(), 21);
	});
});
</script>
</body>

</html>