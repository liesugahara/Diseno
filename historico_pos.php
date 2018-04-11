<html>
<!--Scripts de inicialización-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-ME1K7mHCv4Aeep1OL86E8TKqRUkDyQM&callback=initMap"></script>
<style>
	#map {
	height: 50%;
	width: 100%;
	background: 
	}
</style>
<?php 
	ob_start();
	include 'refresh.php';
	
	ob_clean();
?>  	
<!--Inicialización del mapa-->
<script>
	var map;
	var marker;
	var mark;
	var circle;
	var coordinates = [];
	var markersArray = [];
	
	// FUNCIÓN QUE DESPLAZA EL MARCADOR CUANDO RECIBE LAS NUEVAS COORDENADAS
	 /* function moveToLocation(lat, lng){
		var center = new google.maps.LatLng(lat, lng);
		// using global variable:
		map.panTo(center);
	}  */
		// FUNCIÓN PRINCIPAL QUE SE INICIALIZA EN EL MAPA. ESTÁ DECLARADO EN LA API KEY
	function initMap() {
		//DECLARACIÓN DE LAS VARIABLES DE POSICIÓN Y PARÁMETROS NECESARIOS PARA EL MAPA
		var uluru = {lat:parseFloat('<?php echo $lat;?>'),lng: parseFloat('<?php echo $lon;?>')};
		var parameters = {
		zoom: 15,
		center: uluru
		};
		map = new google.maps.Map(document.getElementById('map'),parameters) 
		 
		
		//MARCADOR INICIAL EN LA POSICIÓN INICIAL DE LA BASE DE DATOS
		function marker1(){
			var marker = new google.maps.Marker({
			position: {lat:11.019216,lng:-74.850367},
			map: map,
			icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
			draggable:true		
		}); 
		return marker;
		}
		var marcador = marker1();
		<!-----------------------------------TESTTTTTTT---------------------------->
		 circle = new google.maps.Circle({
							map: map,
							clickable: false,
							draggable:true,
							// metres
							radius: 80,
							fillColor: '#fff',
							fillOpacity: .6,
							strokeColor: '#313131',
							strokeOpacity: .4,
							strokeWeight: .8
						});	 
						// Attach circle to marker
						circle.bindTo('center', marcador, 'position'); 
		
		<!-----------------------------------TESTTTTTTT---------------------------->
		addMarker(new google.maps.LatLng(uluru),map)
		
		
	} 
	
	//FUNCIÓN QUE EXTRAE LOS VALORES DE POSICIÓN DEL JSON QUE SE GENERA EN LOCATION.PHP								
	setInterval(function reload_map(){
	//INSTRUCCIÓN NECESARIA PARA ACTUALIZAR LA INFORMACIÓN EN BACKGROUND
	$.ajax({
		url: 'location.php',
		dataType: "json",
		success: function (data){
			//EXTRACCIÓN DE VALORES DEL OBJETO DE TIPO JSON IMPORTADO DE LOCATION.PHP	
			var obj_que =	jQuery.parseJSON(JSON.stringify(data));
			$(jQuery.parseJSON(JSON.stringify(data))).each(function() {
			//NUEVA LATITUD Y LONGITUD
			
			var LATITUDE = this.latitud;
			var LONGITUDE = this.longitud;
			
			mycord = new google.maps.LatLng(LATITUDE,LONGITUDE)
			
			coordinates.push(mycord);
			
			var line = new google.maps.Polyline({
				path: coordinates,
				geodesic: true,
				strokeColor: '#FF0000',
				strokeOpacity: 1.0,
				strokeWeight: 2
			});
			line.setPath(coordinates);
			line.setMap(map);
			//CONDICIONAL POR MEDIO DEL CUAL SE AGREGA UN MARCADOR;			
			if (marker != null){									
				marker.setPosition(new google.maps.LatLng(LATITUDE, LONGITUDE));
				/* moveToLocation(parseFloat(LATITUDE),parseFloat(LONGITUDE));	 */ 
			}else{
					addMarker(new google.maps.LatLng(LATITUDE, LONGITUDE), map);
					/* moveToLocation(parseFloat(LATITUDE),parseFloat(LONGITUDE)); */
			}		
			});
			
			
			
				
		},
		error: function() {
			console.log('Error: ' + obj_que);
		}
	});
	//TIEMPO DE ACTUALIZACIÓN DEL MARCADOR: 2 SEGUNDOS
	},2000);
	//FUNCIÓN QUE AGREGA EL MARCADOR
	setInterval(function Historico_pos(){
		
		$.ajax({
			url: 'buscar_historico2.php',
			dataType: "json",
			success: function (data){
				var obj_que1 =	jQuery.parseJSON(JSON.stringify(data));
				
				
					
					for(var i = 0; i< obj_que1.length; i++ ){
						var lat1 = obj_que1[i].latitud;
						var lon1 = obj_que1[i].longitud;
						var google_latlon = new google.maps.LatLng(parseFloat(lat1),parseFloat(lon1));
						
						markersArray.push(google_latlon);
						
						 
		
		
					// Log into the dev bar console whether the marker is inside or outside
					
					}

			},
			error: function() {
			console.log('Error: ' + obj_que1);
			}
		});
	},10000);
	
	setInterval(function markers_show(){
		
		for(var j = 0; j<markersArray.length; j++){
							var markerLat = markersArray[j].lat();
							var markerLng = markersArray[j].lng();
							var bounds = circle.getBounds();

							var myLatLng = new google.maps.LatLng({
								lat:markerLat,
								lng:markerLng
							});
							
							if(bounds.contains(myLatLng)==true){
								var mark = new google.maps.Marker({
								  position: {lat:parseFloat(markerLat), lng:parseFloat(markerLng)},
								  map: map
								}); 
							}if(bounds.contains(myLatLng)==false){
								
							}
						}
	},5000);
	
	function addMarker(latLng, map) {
		marker = new google.maps.Marker({
		position: latLng,
		map: map,
		draggable: false, 
		animation: google.maps.Animation.DROP
		});
		return marker;
	}										
</script>
<head>
</head>
<body>
	<div id = "map"></div>
</body>
</html>
