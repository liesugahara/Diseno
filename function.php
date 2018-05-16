<?php 
header ('Content-Type: text/html;charset=utf-8');

function ejecutarSQLCommand($commando){
	
	$usuario = "ednp";
	$password = "Diseno2018";
	$servidor = "syrus.cf58lmtunvyx.us-east-2.rds.amazonaws.com:3306";
	$basededatos = "syrusR";
						
						
$mysqli = new mysqli($servidor,$usuario,$password,$basededatos);
	
	/* check connection */
if ($mysqli->connect_errno){
	printf("connect failed: %s\n",$mysqli->connect_error);
	exit();
}
	
	if ($mysqli->multi_query($commando)){
		if ($resultset = $mysqli->store_result()){
			while ($row = $resultset->fetch_array(MYSQLI_BOTH)){
				
			}
			$resultset->free();
		}
	}
	$mysqli->close();

}
function getSQLResultSet($commando){
	$mysqli = new mysqli($servidor,$usuario,$password,$basededatos);
/*check connection*/	
if ($mysqli->connect_errno){
	printf("connect failed: %s\n",$mysqli->connect_error);
	exit();
}

if ($mysqli->multi_query($commando)){
	return $mysqli->store_result();
}
$mysqli->close();
}
?>
