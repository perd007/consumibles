<?php require_once('Connections/conexion.php'); ?>
<?php

//recibimos los datos
$id=$_GET["id"];

//eliminamos el colector


mysql_select_db($database_conexion, $conexion);
$sql="delete  FROM  despachos where id_despachos=$id";
$verificar=mysql_query($sql,$conexion) or die(mysql_error());

if($verificar){
	echo"<script type=\"text/javascript\">alert ('Datos Eliminado'); location.href='consultar_despachos.php' </script>";
}
else{
	echo"<script type=\"text/javascript\">alert ('Error'); location.href='consultar_despachos.php' </script>";
	
}//fin de l primer else


?>