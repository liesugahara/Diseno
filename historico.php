<html>
<!--styles-->
<style>
	#map1 {
	height: 65%;
	width: 50%;
	}
</style>
<!--Scripts-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-ME1K7mHCv4Aeep1OL86E8TKqRUkDyQM&callback=initMap1"></script>

<?php 

	ob_start();
	include 'refresh.php';
	
	ob_clean();
?>
<!--Inicialización del mapa-->
	<script>
	  var map1
	  var markersArray = [];
	  var circle;
	  <!--Inicio mapa-->
      function initMap1() {
        var uluru = {lat:parseFloat('<?php echo $lat;?>'),lng: parseFloat('<?php echo $lon;?>')};
        map1 = new google.maps.Map(document.getElementById('map1'), {
          zoom: 16,
          center: uluru
        });
		//Marcador Inicial
        var marker = new google.maps.Marker({
          position: uluru,
          map: map1
        });
		 <!--Marcador draggable por posición con sus caracteristicas-->
		function marker1(){
			var marker = new google.maps.Marker({
			<!--Se ubica en la ultima posición guardada-->
			position: {lat:parseFloat('<?php echo $lat;?>'),lng: parseFloat('<?php echo $lon;?>')},
			map: map1,
			icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
			size: new google.maps.Size(32),
			draggable:true		
		}); 
		return marker;
		}
		// se crea una variable para utilizar la función del marcador
		var marcador = marker1();
		<!--Circulo que limitará los marcadores a mostrar->
		 circle = new google.maps.Circle({
			map: map1,
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
		// Se une el marcador creado al circulo
		circle.bindTo('center', marcador, 'position');
      }
	  
    </script>
<!--Listado de históricos-->	
	<script>
		var dateArray= [];
		var textoBusqueda;
		var textoBusqueda2;		// Variable que guarda los marcadores limitados por la fecha
		// Función que extrae las posiciones de la base de datos
		function buscar2(){
		var textoBusqueda;
		var textoBusqueda2;
		/* var textoBusqueda = $("#busqueda").val()
		var textoBusqueda2 = $("#busqueda2").val() */
		inputDate1 = document.getElementById("inputDate1").value;
		inputDate2 = document.getElementById("inputDate2").value;
		inputTime1 = document.getElementById("inputTime1").value;
		inputTime2 = document.getElementById("inputTime2").value;
		
		
		textoBusqueda = inputDate1 + ' ' + inputTime1;
		textoBusqueda2 = inputDate2 + ' ' + inputTime2;
		console.log(textoBusqueda+" /"+textoBusqueda2);
		if((inputDate1 == '') && (inputTime1 == '') && (inputDate2) && (inputTime2) ){
			textoBusqueda = '';
			textoBusqueda2 = '';
			$("#displayData").html('Falta fecha y hora inicial')	;
		}else if ((inputDate1) && (inputTime1) && (inputDate2=='') && (inputTime2=='')){
			$("#displayData").html('Falta fecha y hora final')
		}else if ((inputDate1) && (inputTime1=='')&& (inputDate2=='') && (inputTime2=='')){
			$("#displayData").html('Falta hora inicial')
		}else if ((inputDate1) && (inputTime1) && (inputDate2) && (inputTime2 == '')){
			$("#displayData").html('Falta hora final')
		}else if ((inputDate1) && (inputTime1) && (inputDate2=='') && (inputTime2)){
			$("#displayData").html('Falta fecha final')
		}else if ((inputDate1) && (inputTime1) && (inputDate2) && (inputTime2=='')){
			$("#displayData").html('Falta hora final')
		}else if ((inputDate1=='') && (inputTime1=='') && (inputDate2=='') && (inputTime2=='')){
			$("#displayData").html('Debe llenar los campos')
		}
		 
		if ((textoBusqueda) && (textoBusqueda2)) {
			for (i=0;i<textoBusqueda.length;i++){
				if(parseInt(textoBusqueda[i])>parseInt(textoBusqueda2[i])){
					$("#displayData").html('Fecha inicial ha sido mal digitada')	
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
							var DATE = obj[i].fecha_hora;
							myCoord = new google.maps.LatLng(parseFloat(LAT),parseFloat(LON));
							hist_path.push(myCoord);
							dateArray.push(DATE);
							markersArray.push(myCoord);
						}
						 var Route_Total = new google.maps.Polyline({
							path: hist_path,
							strokeColor: '#FF0000',
							strokeOpacity: 1.0,
							strokeWeight: 5
						 });
						Route_Total.setPath(hist_path)
						Route_Total.setMap(map1);
					
					$("#displayData").html(hist);
					
			}); 
		
			
		}  else { 
			$("#displayData").html('Falta fecha y hora final');
			
			}
		
	};

		setInterval(function markers_show(){
		for(var j = 0; j<markersArray.length; j++){
							var markerLat = markersArray[j].lat();
							var markerLng = markersArray[j].lng();
							var date_mark = dateArray[j];
							var bounds = circle.getBounds();

							var myLatLng = new google.maps.LatLng({
								lat:markerLat,
								lng:markerLng
							});
							
							if(bounds.contains(myLatLng)==true){
								var mark = new google.maps.Marker({
								  position: {lat:parseFloat(markerLat), lng:parseFloat(markerLng)},
								  map: map1,
								  title:date_mark	
								}); 
							}if(bounds.contains(myLatLng)==false){
								
							}
						}
	},1000);
</script>
	
	<head>
		<div>
			<img src = "un.png" align="left" width = "60px" heigt="60px">
			<center>
				<h1 style = "font: oblique bold 120% cursive;font-size:30px">HISTORICAL
				</h1>
			</center>
		</div>
	</head>
	<body>
		<center><div id = 'fecha_hora' style = "width:50%;height:25%;border:2px solid gray">
		<form accept-charset="utf-8" method="POST">
			<center><h1 style = "font: oblique bold 120% cursive;font-size:20px">Filtro por fecha y hora</h1><center>
			<p style = "font: oblique bold 120% cursive;font-size:15px">Fecha1:<input type="date" id="inputDate1">
			Hora1:<input type="time" id="inputTime1"></p>
			<p style = "font: oblique bold 120% cursive;font-size:15px">Fecha2:<input type="date" id="inputDate2">
			Hora2:<input type="time" id="inputTime2"></p>
		<button onclick="buscar2()" type="button">Buscar</button>
		</form>
		</div></center>
		<div id = "contenedor" >
			<div id = 'displayData' style = 'border:2px solid gray;float:left;width:49.2%;height:65%'>
			</div>
			<div id = 'map1' style  = "border:2px solid gray" ></div>
		</div>
		
	</body>
	
</html>
