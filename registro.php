<?php 
include ('function.php');
$lat = $_GET['latitud'];
$lon = $_GET['longitud'];
$date = $_GET['fecha_hora'];

ejecutarSQLCommand("UPDATE vehicle_info SET latitud= '$lat',longitud='$lon',fecha_hora='$date';");

?>
