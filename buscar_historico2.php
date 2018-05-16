<?php 
	$usuario = "ednp";
	$password = "Diseno2018";
	$servidor = "syrus.cf58lmtunvyx.us-east-2.rds.amazonaws.com:3306";
	$basededatos = "syrusR";
	$mensaje1 = "";
	$mensaje = "";
	$info = "";
	$info1 = "";

				
	// creación de la conexión a la base de datos con mysql_connect()
	$conexion = mysqli_connect( $servidor, $usuario, $password ) or die ("No se ha podido conectar al servidor de Base de datos");
						
	// Selección del a base de datos a utilizar
	$db = mysqli_select_db( $conexion, $basededatos ) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );
						
						
	if(isset($date1,$date2)){
		$query = "SELECT * FROM vehicle2_info WHERE fecha_hora BETWEEN '".$date1."' AND '".$date2."'";
		$resultado = mysqli_query( $conexion, $query ) or die ( "Algo ha ido mal en la consulta a la base de datos");
	}	
		$mensaje.= "<table class='tabla_datos'><thead><tr><td>ID</td><td>Latitud</td><td>Longitud</td><td>Fecha y hora</td></tr></thead><tbody>";
		
		while($resultados = mysqli_fetch_array($resultado)) {
			$id = $resultados['id'];
			$latitud = $resultados['latitud'];
			$longitud = $resultados['longitud'];
			$fecha = $resultados['fecha_hora'];
			$data[] = $resultados;		
			//Output
			$mensaje.= "</tr><td>$id</td><td>$latitud</td><td>$longitud</td><td>$fecha</td></tr></tbody>";
			
		};//Fin while $resultados
	// establecer y realizar consulta. guardamos en variable.
	// Se convierten los datos en un objeto JSON
	 /* ob_end_clean();  */
	
	
	
	//Return en tipo JSON a historico.php
	echo $mensaje.json_encode($data);
	
?>
