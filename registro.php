<?php 
include ('function.php');
$lat = $_GET['latitud'];
$lon = $_GET['longitud'];
$date = $_GET['fecha_hora'];

ejecutarSQLCommand("INSERT INTO vehicle_info (latitud,longitud,fecha_hora) VALUES('$lat','$lon','$date')");

?>
