var markerCluster;
var entryList;
var entries;
var map;
var area;
var coordCercle;
var monCercle;
$(document).ready(function()
{
	var currentUrl = window.location.href;
    $("#filterForm").validate({
        rules: {
            city: {required: true},
        },
        messages : {
	        city : {
	            required :"Veuillez définir la ville",
	        }
	    },
        submitHandler: function(form) {
			hideFamilyFilter();
			setTimeout(function (){refresh(false);}, 100);
            return false;
        }
	});
	initMap();
	refresh(true);

});

$('.filters #reset_btn').click(function(event){
	event.preventDefault();
	area = 0;
	$('#city').val('');
	$.ajax({
		type     : 'POST',
		url      : '/action-test1-GetMapCoord',
		async    : false,
		dataType : 'json',
		data     : "action=reset",
		success  : function(data)
		{
			if (data.state == "RESETED") {
				location.reload();
			}
		},
		error	 : function(xhr, thrownError)
		{
			console.log('### ERROR ###  =>  ' + thrownError);
			console.log(xhr);
		}
	});
});

function checkEmpty(data) {
  return (data !== null ||  data !== undefined);
}

function initMap()
{
	prevMarker = null,
	markers    = new Array(),
	infoBulles = new Array();
	// initialisation :
	initializeMap();
	if (markerCluster === undefined) {
		setMarkerCluster();
	}
	generateMapPoints();
	adjustDimensions();
}

function compareTime(dateString, now) {
	return (now - dateString) > 86400000;
}

function addPoint(id, data)
{
	if (data === undefined || data === null || data === 0) {
		return;
	}
	// création de la coordonnée :
	var latLng = new google.maps.LatLng(parseFloat(data.coord.lat), parseFloat(data.coord.lng));
	// création du marker
	var marker = new google.maps.Marker({
		position  : latLng,
		map       : map,
		id_marker : id,
		clickable : true,
		optimized : false,
		// icon      : icone
	});
	markers[id] = marker;
	markerCluster.addMarker(marker);

	var content ='<div class="inmymap">'
				+'<div>' + data.product_name + '</div>'
				+'<hr>'
				+'<div >' + data.address + '</div>'
				+'<hr>'
				+'<div ><a href="' + data.url + '">Voir le produit</a></div>'
				+'<hr>'
				+ '<div class="actionsBubble" style="margin-top: 10px;">'
				+ '</div>';
				+ '</div>';

	// affichage d'une popup avec la libellé de la résidence au click sur le marker
	google.maps.event.addListener(marker, 'click', showInfoBubble);
	function showInfoBubble()
	{
		var bulle = infoBulles[marker.id_marker];
		if (bulle == undefined) {
			// Nouvelle version de l'infobulle
			bulle = new InfoBubble({
				map       : marker.getMap(),
				content   : content,
				position  : marker.position,
				height    : 20
			});
			infoBulles[marker.id_marker] = bulle;
		}
		bulle.open(marker.getMap() ,marker);
	}

}

function generateMapPoints(ids)
{
	markers    = new Array();
	infoBulles = new Array();
	if (ids === undefined) {
		entries = entryIds;
	} else {
		entries = ids;
	}
	createMap(entries);
}

function createMap(entryList) {
	$('#message').hide();
	$('#message').html('');
	// création de la map
	markers = new Array();
	if (markerCluster === undefined) {
		setMarkerCluster();
	}
	for (var i in entryList) {
		r = entryList[i];
		addPoint(i, r);
		infoBulles[i] = null;
	}

	// définitions du bounds
	var myPoints = [];
	for(var i in markers) {
		latLng = new google.maps.LatLng(markers[i].position.lat() , markers[i].position.lng());
		if (markers[i].position.lat() != 0 && markers[i].position.lng() != 0) {
			myPoints.push(latLng);
			markerCluster.addMarker(markers[i]);
		}
	}
	if (myPoints.length > 0) {
		var bounds = new google.maps.LatLngBounds();
		for(var i = 0; i < myPoints.length; i++){
			bounds.extend(myPoints[i]);
		}
		map.fitBounds(bounds);
	}
	if (markers.length == 0) {
		$('#message').show();
		$('#message').html('Aucun point de vente pour ' + $('#city').val() + ' ');
	}
	if (area > 0 && coordCercle != null) {
		$('#area').val(area/1000);
		latLng = new google.maps.LatLng(coordCercle.lat, coordCercle.lng);
		optionsCercle = {
			map: map,
			center: latLng,
			radius: area,
			fillColor: "#000", // couleur de remplissage du cercle
			fillOpacity: 0.2, // opacité de remplissage du cercle
			strokeColor: "#000", // couleur de la bordure du cercle
			strokeOpacity: 0.9 // opacité de la bordure du cercle
		}
		monCercle = new google.maps.Circle(optionsCercle);
	} else {
		$('#area').val("");
	}
	hideFamilyFilter()
}

function initializeMap()
{
	var myOptions = {
		zoom      : 7,
		center    : {
            lat: 48.118212,
            lng: -1.682448
        },
		minZoom   : 1,
		maxZoom   : 22,
		mapTypeId : google.maps.MapTypeId.ROADMAP,
		noClear   : false,
	};

	map = new google.maps.Map(document.getElementById("map") , myOptions);
	google.maps.event.addListener(map, 'idle', function() {
		google.maps.event.trigger(map, 'resize');
	});
}

// Mise en place d'un Cluster pour les marqueurs
function setMarkerCluster()
{
    markerCluster = new MarkerClusterer(map, markers, {
		zoomOnClick: true,
		maxZoom: 15, // Niveau de zoom maximal qu'un marqueur peut faire partie d'un cluster
		imagePath: '/images/markercluster/m', // Icône pour les marqueurs

	});
	google.maps.event.addListener(markerCluster, 'clusterclick', function(cluster){
	    map.setCenter(cluster.getCenter());
	    map.setZoom(map.getZoom() + 3);
	});
}

function refresh(isLoading)
{
	var params = '';
	if (!isLoading) {
		params 	   = 'action=getEntryList&' + $('#filterForm').serialize();
	} else {
		area = areaLoading;
		params 	   = 'action=getEntryList&city=' + $('#city').val() + '&area=' +areaLoading;
	}
	var filterCustom = $('#filterForm').serializeArray();
	$.ajax({
		type     : 'POST',
		url      : '/action-test1-GetMapCoord',
		async    : false,
		dataType : 'json',
		data     : params,
		success  : function(data)
		{
			// Rafraichissement de la carte et de la liste
			entries = data.ids;
			area = data.area;
			coordCercle = data.coord;

		},
		error	 : function(xhr, thrownError)
		{
			console.log('### ERROR ###  =>  ' + thrownError);
			console.log(xhr);
		}
	});
	setMapOnAll(null);
	markerCluster.clearMarkers();

	if (monCercle !== undefined) {
		monCercle.setMap(null);
	}

	if (entries.length > 0) {
		createMap(entries);
	} else if(!isLoading) {
		$('#message').show();
		$('#message').html('Aucun point pour ' + $('#city').val() + ' ');
	} else {
		$('#message').show();
		$('#message').html('Récupération des points...');
	}
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {
	for (var i = 0; i < markers.length; i++) {
		if (markers[i] !== undefined) {
			markers[i].setMap(map);
		}
		if(infoBulles[i] != undefined){
			infoBulles[i].close();
		}
	}

}

function adjustDimensions()
{
	// Calcul de dimension pour l'affichage de la carte
	var windowHeight = $(window).height(),
	div_map          = $('#map'),
	div_mapHeight    = 0,
	fullWidth        = $('.fullWidth'),
	entryList        = $('#list'),
	explication      = $('.explication'),
	allHeight        = $('#header').innerHeight()
					  + parseInt($('#wrapper').css('padding-bottom').split('px')[0])
					  + explication.innerHeight()
					  + $('#footer h2').outerHeight(true),
	offset           = 25,
	totalHeight      = windowHeight - allHeight - offset;
	div_mapHeight = div_map.height();
	div_map.height(800);

	// Définition de la largeur de la carte
	div_map.width('99%');

	// Placement de la hauteur de l'explication
	explication.css({
		'top' : div_map.height() + 110
	});
}

function hashCode(str) {
  return str.split('').reduce(function(prevHash, currVal) {
    (((prevHash << 5) - prevHash) + currVal.charCodeAt(0))|0, 0});
}
function updateOrCreateDate() {
	var lastUpdate;
	var lastUpdateTimestamp = new Date().getTime().toString();
	if (localStorage.getItem("lastUpdate")) {
		lastUpdate = JSON.parse(localStorage.getItem("lastUpdate")),
    	dateString = lastUpdate.timestamp,
    	now = new Date().getTime().toString();
    	if (!compareTime(dateString, now)) {
    		lastUpdateTimestamp = dateString;
    	} else {
    		localStorage.clear();
    	}
	}
	try {
		var object = {value: "lastUpdate", timestamp: lastUpdateTimestamp}
		localStorage.setItem("lastUpdate", JSON.stringify(object));
	} catch(e) {
		if (e.code == 22) {
			localStorage.clear();
			localStorage.setItem(hashCode(params), JSON.stringify(data));
		}

	}
}

function hideFamilyFilter() {
	$('#pending_family').hide();
	$('#familiesFilter').hide();
}
