<?php
	// Ejemplo de conexión a base de datos MySQL con PHP.
						//
						//
						
						// Datos de la base de datos
						$usuario = "liesugahara";
						$password = "Lccjhg1995";
						$servidor = "mydb.cakorcerkah4.us-east-2.rds.amazonaws.com";
						$basededatos = "Test";
						
						// creación de la conexión a la base de datos con mysql_connect()
						$conexion = mysqli_connect( $servidor, $usuario, $password ) or die ("No se ha podido conectar al servidor de Base de datos");
						
						// Selección del a base de datos a utilizar
						$db = mysqli_select_db( $conexion, $basededatos ) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );
						// establecer y realizar consulta. guardamos en variable.
						$consulta = "SELECT * FROM vehicle_info order by id desc limit 1";
                        $resultado = mysqli_query( $conexion, $consulta ) or die ( "Algo ha ido mal en la consulta a la base de datos");
                       
                        $data = array();
                            if($resultado) {
                            $data = mysqli_fetch_assoc($resultado);
                            }
                            echo json_encode($data); // Se convierten los datos en un objeto JSON
?>
