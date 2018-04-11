<!DOCTYPE html>
<html>
<!-- COMIENZO DEL CODIGO -->
<title>Web Position</title>
		<head>
			<meta charset="utf-8">

			<link rel="stylesheet" href="semantic/semantic/dist/semantic.min.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<script src="semantic/semantic/dist/semantic.min.js"></script>
			<script src="ajax.js"></script>


			<style>
			#map {
			height: 400px;
			width: 100%;
			 }
			</style>


		</head>

			<body>
				<!--Menu Lateral -->
				    <div  id="menu1" class="ui sidebar inverted vertical menu">
				    <a href="historico.php" class="item"><i class="book icon"></i> Registros</a>
				  </div>

				<div class="pusher"> <!--Hace que todo dentro de el se mueva cuando sale el menu lateral -->

				<!-- Menu y titulo de la pagina -->
				  <div class="ui basic icon menu">
				    <a id="boton1" class="item">
				      <i class="sidebar icon"></i>
				       menu
				    </a>
				    <div class="ui header large blue item">Dynamic Solutions <small>Telemetry</small> </div>
				      </div>

				<!-- Cuadro de coordenadas -->
				  <h3 class="ui center aligned blue header">Información</h3>
				<div class="ui container">
				<table id="contenido" class="ui small celled table">
				  <thead>
				      <tr>
				      <th class="center aligned" >Latitud</th>
				      <th class="center aligned">Longitud</th>
				      <th class="center aligned">Fecha y hora</th>
				    </tr></thead>
				    <tbody>
				      <tr>
				        <td class="center aligned">  </td>
				        <td class="center aligned">  </td>
				        <td class="center aligned">  </td>
				      </tr>

				    </tbody>
				</table>
				</div> 
				<br>

						<!--DIV QUE CONTIENE EL DIV DONDE SE MUESTRA EL MAPA DE GOOGLE MAPS-->
						<div class="ui container" id= "container">


						<!--CODIGO PHP DONDE IMPORTAN DATOS DEL ARCHIVO REFRESH.PHP, PERO ELIMINANDO LA INFORMACI�N QUE MUESTRA-->
            <?php
							ob_start();
							include 'refresh.php';
							ob_clean();
						?>
						<!--DIV DEL MAPA-->
						<fieldset>
            <div id="map"></div>
						</fieldset>
						
						<!--SCRIPT DEL MAPA-->
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
								</div>
								</div>

								<script>
						   menu1=document.getElementById("menu1");
						    boton1=document.getElementById('boton1');
						  $('#boton1').click(function(){
						    $('#menu1').sidebar('toggle')
						;
						    });
						    </script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBG68IWgodZnKlxU-c49MKUeufAyI611r0&callback=initMap"></script>
			</body>

</html>
