<html>

<!--Scripts de inicialización-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
<script src="ajax.js"></script>	
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-ME1K7mHCv4Aeep1OL86E8TKqRUkDyQM&callback=initMap"></script>


<!--CODIGO PHP DONDE IMPORTAN DATOS DEL ARCHIVO REFRESH.PHP, PERO ELIMINANDO LA INFORMACIÓN QUE MUESTRA-->
<?php 
	ob_start();
	include 'refresh.php';
	ob_clean();
?>  	
	
<!--SCRIPT DEL MAPA-->             
<script>
	var map;
	var marker;
	var mark;
	var circle;
	var coordinates = [];
	var coordinates1 = [];
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
	setInterval(function reload_map1(){
		<!--NUEVO AJAX PARA POSICIONAMIENTO DE SEGUNDO VEHICULO-->
	$.ajax({
		url: 'location2.php',
		dataType: "json",
		success: function (data2){
			//EXTRACCIÓN DE VALORES DEL OBJETO DE TIPO JSON IMPORTADO DE LOCATION.PHP	
			var obj_que1 =	jQuery.parseJSON(JSON.stringify(data2));
			$(jQuery.parseJSON(JSON.stringify(data2))).each(function() {
			//NUEVA LATITUD Y LONGITUD
			var LATITUDE = this.latitud;
			var LONGITUDE = this.longitud;
			var time = this.fecha_hora;
			var div_vehicle = document.getElementById("vehicle2")
			var imp = LATITUDE + "  " + LONGITUDE + "  " + time;
			document.getElementById('vehicle2').innerHTML=imp;
			mycord1 = new google.maps.LatLng(LATITUDE,LONGITUDE)			
			coordinates1.push(mycord1);	
			var line1 = new google.maps.Polyline({
				path: coordinates,
				geodesic: true,
				strokeColor: '#0BF607',
				strokeOpacity: 1.0,
				strokeWeight: 2
			});
			line1.setPath(coordinates1);
			line1.setMap(map);
			//CONDICIONAL POR MEDIO DEL CUAL SE AGREGA UN MARCADOR;			
			if (mark != null){									
				mark.setPosition(new google.maps.LatLng(LATITUDE, LONGITUDE));
				/* moveToLocation(parseFloat(LATITUDE),parseFloat(LONGITUDE));	 */ 
			}else{
					mark = new google.maps.Marker({
					position: new google.maps.LatLng(LATITUDE, LONGITUDE),
					icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
					map: map,
					draggable: false, 
					animation: google.maps.Animation.DROP
					});
					/* moveToLocation(parseFloat(LATITUDE),parseFloat(LONGITUDE)); */
				}		
			});
		},
		error: function() {
			console.log('Error: ' + obj_que1);
		}
		
	})
	<!--NUEVO AJAX-->
	},5000);
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
	function addMarker(latLng, map) {
		marker = new google.maps.Marker({
		position: latLng,
		map: map,
		draggable: false, 
		icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
		animation: google.maps.Animation.DROP
		});
		return marker;
	}										
</script>
		
<!--Cabecera-->	
<head>				
<div class = "encabezado"  >
<img src = "un.png" align="left" width = "60px" heigt="60px">
<!-- TITULO PRINCIPAL-->
<center><h1 style = "font: oblique bold 120% cursive;font-size:30px">DYNAMIC SOLUTIONS
<small>Telemetry</small></h1></center>
</div>		
</head>
<!--Cuerpo de la página web-->
<body>
	<center><fieldset style="width:30%;color:black">
		<!--DIV DONDE SE MUESTRA LA INFORMACIÓN LATITUD,LONGITUD,HORA-->
		<legend style = "background:#BDBDBD;border:2px solid gray"><b>Information Vehicle1</b></legend>
		<div id="contenido" style = "background:#BDBDBD;border:2px solid gray ">			
		</div>	
		
		</fieldset> 
		<fieldset style="width:30%;color:black">
		<!--DIV DONDE SE MUESTRA LA INFORMACIÓN LATITUD,LONGITUD,HORA-->
		<legend style = "background:#BDBDBD;border:2px solid gray"><b>Information vehicle2</b></legend>
		<div id="vehicle2" style = "background:#BDBDBD;border:2px solid gray ">			
		</div>	
		
		</fieldset> 
		<div style = "float:left">
		<!-- <h1 style = "color:black;font: oblique bold 120% cursive;font-size:30px;background:#BDBDBD">Menú</h1> -->
		<a href = "historico.php" style = "color:black;font: oblique  120% cursive;font-size:13px" >Historical</a>
		<!--- <a href = "historico2.php" style = "color:black;font: oblique  120% cursive;font-size:13px" >Historical 2</a> --->
		</div>
		</center>
	<!--TITULO DEL MAPA	-->
	
	<center><h1 style = "color:black;font: oblique bold 120% cursive;font-size:20px"> Real-Time Tracking </h1></center>


<div id="map" style = "border:2px solid gray" ></div>

</body>


<!--ESTILO NECESARIO PARA EJECUTAR EL MAPA-->   
<style>
	#map {
	height: 50%;
	width: 100%;
	background: 
	}
</style>
</html>
