<?php 
	$usuario = "liesugahara";
	$password = "Lccjhg1995";
	$servidor = "dbtest.cakorcerkah4.us-east-2.rds.amazonaws.com:3306";
	$basededatos = "Test";
	$mensaje1 = "";
	$mensaje = "";
	$info = "";
	$info1 = "";

	
	
					
	// creación de la conexión a la base de datos con mysql_connect()
	$conexion = mysqli_connect( $servidor, $usuario, $password ) or die ("No se ha podido conectar al servidor de Base de datos");
						
	// Selección del a base de datos a utilizar
	$db = mysqli_select_db( $conexion, $basededatos ) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );
						
						
	
		$query = "SELECT * FROM vehicle_info ";
		$resultado = mysqli_query( $conexion, $query ) or die ( "Algo ha ido mal en la consulta a la base de datos");
		
		
		 $data = array();
         while($resultados = mysqli_fetch_array($resultado)) {
			
			$data[] = $resultados;		
			//Output
			
		};//Fin while $resultados
	
	echo json_encode($data);
	
	
	
	

	
	
	
?>
