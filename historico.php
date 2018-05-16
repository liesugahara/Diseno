<html>
<!--styles-->
<style>
	#map1 {
	height: 65%;
	width: 49.8%;
	border: 2px solid gray;
	}
</style>
<!--Scripts-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA-ME1K7mHCv4Aeep1OL86E8TKqRUkDyQM&callback=initMap1"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<?php 

	ob_start();
	include 'refresh.php';
	
	ob_clean();
?>
<!--Inicialización del mapa-->
<script>
	  var map1
	  var markersArray = [];
	  var markersArray2 =[];
	  var circle;
	  <!--Inicio mapa-->
    function initMap1() {
        var uluru = {lat:parseFloat('<?php echo $lat;?>'),lng: parseFloat('<?php echo $lon;?>')};
        map1 = new google.maps.Map(document.getElementById('map1'), {
          zoom: 16,
          center: uluru
        });
		//Marcador draggable por posición con sus caracteristicas-->
		function marker1(){
			var marker = new google.maps.Marker({
				//SE UBICA EN LA ULTIMA POSICIÓN GUARDADA
				position: {lat:parseFloat('<?php echo $lat;?>'),lng: parseFloat('<?php echo $lon;?>')},
				map: map1,
				icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png',
				size: new google.maps.Size(32),
				draggable:true		
			}); 
		return marker;
		}
		// SE CREA UNA VARIABLE PARA UTILIZAR LA FUNCIÓN DEL MARCADOR
		var marcador = marker1();
		//CIRCULO QUE LIMITARÁ MÁS ADELANTE LOS MARCADORES A MOSTRAR
		circle = new google.maps.Circle({
			map: map1,
			clickable: false,
			editable:true,
			draggable:true,
			// metres
			radius: 80,
			fillColor: '#fff',
			fillOpacity: .6,
			strokeColor: '#313131',
			strokeOpacity: .4,
			strokeWeight: .8
		});	 
		// SE UNE EL MARCADOR CREADO AL CIRCULO
		circle.bindTo('center', marcador, 'position');
    } 
    </script>
<!--Listado de históricos-->	
<script>
//VARIABLES INICIALES
var dateArray= [];
var dateArray2= [];
var textoBusqueda;
var textoBusqueda2;	
var rpm_arr = [];	
	//FUNCIÓN GENERAL
	function buscar2(){
			var markers = [];
			var markers2 = [];
			markersArray = [];
			markersArray2 = [];
			rpm_arr = [];
			dateArray2= [];
			<!--OBTENCIÓN DE INFORMACIÓN DE LOS INPUTS DATES--->
			inputDate1 = document.getElementById("inputDate1").value;
			inputDate2 = document.getElementById("inputDate2").value;
			inputTime1 = document.getElementById("inputTime1").value;
			inputTime2 = document.getElementById("inputTime2").value;
			textoBusqueda = inputDate1 + ' ' + inputTime1;
			textoBusqueda2 = inputDate2 + ' ' + inputTime2;
			<!--CONDICIONALES PARA LIMITAR LOS INPUTS DATES-->
			if((inputDate1 == '') && (inputTime1 == '') && (inputDate2) && (inputTime2) ){
				textoBusqueda = '';
				textoBusqueda2 = '';
				alert('Falta fecha y hora inicial');
				}else if ((inputDate1) && (inputTime1) && (inputDate2=='') && (inputTime2=='')){
					alert('Falta fecha y hora final')
					}else if ((inputDate1) && (inputTime1=='')&& (inputDate2=='') && (inputTime2=='')){
						alert('Falta hora inicial')
						}else if ((inputDate1) && (inputTime1) && (inputDate2) && (inputTime2 == '')){
							alert('Falta hora final')
							}else if ((inputDate1) && (inputTime1) && (inputDate2=='') && (inputTime2)){
								alert('Falta fecha final')
								}else if ((inputDate1) && (inputTime1) && (inputDate2) && (inputTime2=='')){
									alert('Falta hora final')
									}else if ((inputDate1=='') && (inputTime1=='') && (inputDate2=='') && (inputTime2=='')){
										alert('Debe llenar los campos')
									}							
		 
			if ((textoBusqueda) && (textoBusqueda2)) {
				<!--CONSULTA PARA VEHÍCULO 1-->
				$.post("buscar_historico.php", 
				{
				valorBusqueda: textoBusqueda,
				valorBusqueda2: textoBusqueda2
				}, 
				function(mensaje) {
					if(mensaje[8] == 'b'){
						alert('No hay información para vehículo 1 en las fechas digitadas. Pulse aceptar para continuar con el otro vehiculo')
					}else{
						var arrayDeCadenas = mensaje.split("[");
						var hist = arrayDeCadenas[0];
						var json = "[" + arrayDeCadenas[1];
						var obj = eval("(" + json + ")");
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
						 })
					}
				}); 
			<!--FIN CONSULTA PARA VEHÍCULO 1-->
			<!---- CONSULTA PARA VEHÍCULO 2----->
				$.post("buscar_historico2.php", 
				{
				valorBusqueda: textoBusqueda,
				valorBusqueda2: textoBusqueda2
				}, 
				function(mensaje1) {
					if (mensaje1[8] == 'b' ){
						alert('No hay información para vehículo 2 en las fechas digitadas. Pulse aceptar para continuar con el otro vehiculo');	
					}else{
						var arrayDeCadenas2 = mensaje1.split("[");
						var hist2 = arrayDeCadenas2[0];
						var json2 = "[" + arrayDeCadenas2[1];
						var obj2 = eval("(" + json2 + ")");
						var hist_path2 = [];
						for (i=0; i < obj2.length;i++){
							var ID2 = obj2[i].id;
							var LAT2 = obj2[i].latitud;
							var LON2 = obj2[i].longitud;
							var DATE2 = obj2[i].fecha_hora;
							var rpm = obj2[i].rpm;
							myCoord2 = new google.maps.LatLng(parseFloat(LAT2),parseFloat(LON2));
							hist_path2.push(myCoord2);
							dateArray2.push(DATE2);
							markersArray2.push(myCoord2);
							rpm_arr.push(parseFloat(rpm));
						}	
						var Route_Total = new google.maps.Polyline({
							path: hist_path2,
							strokeColor: '#0BF607',
							strokeOpacity: 1.0,
							strokeWeight: 5
						});	
					}
				}); 
		<!----- FIN CONSULTA PARA VEHÍCULO 2---->
			}  else { 
			alert('Falta fecha y hora final');
			}
		<!--FUNCIÓN QUE PERMITE DIFERENCIAR LOS VEHICULOS MEDIANTE CHECKBOX-->	
		setInterval(function markers_show(){
			var checkBox = document.getElementById("myCheck");
			var checkBox2 = document.getElementById("myCheck2");
			if (checkBox.checked == true){
				for(var j = 0; j<markersArray.length; j++){
					var markerLat = markersArray[j].lat();
					var markerLng = markersArray[j].lng();
					var date_mark1 = dateArray[j];
					var bounds = circle.getBounds();
					var myLatLng = new google.maps.LatLng({
						lat:markerLat,
						lng:markerLng
					});
					if(bounds.contains(myLatLng)==true){
						var mark = new google.maps.Marker({
							position: {lat:parseFloat(markerLat), lng:parseFloat(markerLng)},
							icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
							map: map1,
							title:date_mark1	
						}); 		
						markers.push(mark);		
					}else{}
				}
			}else{
				setMapOnAll(null,markers);
			}	
			if (checkBox2.checked == true){
				for(var j = 0; j<markersArray2.length; j++){
					var markerLat = markersArray2[j].lat();
					var markerLng = markersArray2[j].lng();
					var date_mark2 = dateArray[j];
					var bounds = circle.getBounds();
					var myLatLng = new google.maps.LatLng({
						lat:markerLat,
						lng:markerLng
					});
					if(bounds.contains(myLatLng)==true){
						var mark2 = new google.maps.Marker({
							position: {lat:parseFloat(markerLat), lng:parseFloat(markerLng)},
							icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
							map: map1,
							title:date_mark2	
						}); 
						markers2.push(mark2);
					}else{}
				}
			}else{
				setMapOnAll(null,markers2);
			}
		},1000);
	};
	
	//FUNCIÓN QUE ELIMINA LOS MARCADORES 
	function setMapOnAll(map,mark_num){
		for (i=0;i<mark_num.length;i++){
				mark_num[i].setMap(map);
			}
	}
</script>
<!--Gráfica para rpm-->
<script>
function grafica(){
		$(function() { 
			var myChart = Highcharts.chart('displayData', {
				chart: {
					type: 'line'
				},
				title: {
					text: ''
				},
				xAxis: {
					title: {
						text: 'Date'
					},
					type: 'datetime',  
					categories: dateArray2, 
				},
				series: [{
					name: 'Rpm',
					data: rpm_arr
					
				}/* , {
					name: 'Humidity (%)',
					data: data2
				} */]
			});
		});
	};
        
</script>
	<head>
		<div>
			<img src = "un.png" align="left" width = "60px" heigt="60px">
			<center>
				<h1 style = "font: oblique bold 120% cursive;font-size:30px">HISTORICAL VEHICLE 1
				</h1>
			</center>
		</div>
	</head>
	<body>
		<center><div id = 'fecha_hora' style = "width:40%;height:24.5%;border:2px solid gray">
		<form accept-charset="utf-8" method="POST">
			<center><h1 style = "font: oblique bold 120% cursive;font-size:20px">Filter</h1><center>
			<p style = "font: oblique bold 120% cursive;font-size:15px">Date 1:<input type="date" id="inputDate1">
			Time 1:<input type="time" id="inputTime1"></p>
			<p style = "font: oblique bold 120% cursive;font-size:15px">Date 2:<input type="date" id="inputDate2">
			Time 2:<input type="time" id="inputTime2"></p>
			<button onclick="buscar2()" type="button">Search</button>
			
			
			Vehicle 1: <input type="checkbox" id="myCheck" >
			Vehicle 2: <input type="checkbox" id="myCheck2">
			<button onclick="grafica()" type="button">Graficar</button>
		
		</form>
		</div></center>
		
		<div id = "contenedor" style ='width=100%'>
			<center><div id = 'map1'style ='float:left'></div>
			<div id = 'displayData' style='float:right;border:2px solid gray;width:49.6%;height:65%'></center>
				
			</div><!--
			<div id = 'displayData2' style = 'border:2px solid gray;float:right;width:28%;height:65%;overflow:auto'>
			</div>-->
			
		</div>
		
		
	</body>
	
</html>
