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
						console.log(markersArray[0]);
						 
		
		
					// Log into the dev bar console whether the marker is inside or outside
					
					}

			},
			error: function() {
			console.log('Error: ' + obj_que);
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
								var marker = new google.maps.Marker({
								  position: {lat:parseFloat(markerLat), lng:parseFloat(markerLng)},
								  map: map
								}); 
							}else{
								
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
		
<!--Script para listar los historicos-->
<script>

function buscar(){
	var textoBusqueda = $("#busqueda").val()
	var textoBusqueda2 = $("#busqueda2").val()
	
	
	if ((textoBusqueda) && (textoBusqueda2)) {
		for (i=0;i<textoBusqueda.length;i++){
			if(parseInt(textoBusqueda[i])>parseInt(textoBusqueda2[i])){
				$("#resultadoBusqueda").html('Fecha inicial ha sido mal digitada')	
				console.log(textoBusqueda[i])		
			}
		}
        $.post("buscar_historico.php", 
		{
		valorBusqueda: textoBusqueda,
		valorBusqueda2: textoBusqueda2
		}, 
		
		function(mensaje) {
				var arrayDeCadenas = mensaje.split("[");
				var hist = arrayDeCadenas[0];
				var json = "[" + arrayDeCadenas[1];
				var obj = eval("(" + json + ')');
				
					var hist_path = [];
					for (i=0; i < obj.length;i++){
						var ID = obj[i].id;
						var LAT = obj[i].latitud;
						var LON = obj[i].longitud;
						myCoord = new google.maps.LatLng(parseFloat(LAT),parseFloat(LON));
						hist_path.push(myCoord);
					}
					 var Route_Total = new google.maps.Polyline({
						path: hist_path,
						strokeColor: '#FF0000',
						strokeOpacity: 1.0,
						strokeWeight: 5
					 });
					Route_Total.setPath(hist_path)
					Route_Total.setMap(map);
				
				$("#resultadoBusqueda").html(hist);
				
        }); 
	
		
    }  else { 
        $("#resultadoBusqueda").html('No se encontraron datos');
		
		}
	
	
};
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
		<legend style = "background:#BDBDBD"><b>Information</b></legend>
		<div id="contenido" style = "background:#BDBDBD ">			
		</div>					
		</fieldset></center>
	<!--TITULO DEL MAPA	-->
	<center><h1 style = "color:black;font: oblique bold 120% cursive;font-size:20px"> DISPLAYING POSITION </h1></center>
<div id= "container" style="border:2px solid gray;width:50%;float:right;">
<!--DIV DEL MAPA-->
<div id="map" ></div>
</div>
<div style="border:2px solid gray;float:left:width:50%;height:50%;"><h1 style = "color:black;font: oblique bold 120% cursive;font-size:40px">Histórico</h1>
	
	
	
	
<div id = "resultado1" >
<form accept-charset="utf-8" method="POST">
	Fecha1:<input type="text" name="busqueda" id="busqueda" value="" placeholder="" maxlength="30" autocomplete="off"  onkeyup = "buscar();" />
	Fecha2:<input type="text" name="busqueda2" id="busqueda2" value="" placeholder="" maxlength="30" autocomplete="off" onkeyup = "buscar();"/>
	
</form>


</div>	



<div id="resultadoBusqueda" style = "width:49.5%;height:50%;overflow:auto;border:1px solid gray;align:right">
</div>






</div>

</body>


<!--ESTILO NECESARIO PARA EJECUTAR EL MAPA-->   
<style>
	#map {
	height: 50%;
	width: 100%;
	}
</style>
</html>
