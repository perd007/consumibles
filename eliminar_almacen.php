<?php require_once('Connections/conexion.php');





//recibimos los datos
$id=$_GET["id"];


mysql_select_db($database_conexion, $conexion);
$query_almacen = "SELECT * FROM almacen where id_almacen=$id";
$almacen = mysql_query($query_almacen, $conexion) or die(mysql_error());
$row_almacen = mysql_fetch_assoc($almacen);
$totalRows_almacen = mysql_num_rows($almacen);

$query_almacen2 = "SELECT sum(cantidad) FROM almacen where consumible='$row_almacen[consumible]'";
$almacen2 = mysql_query($query_almacen2, $conexion) or die(mysql_error());
$row_almacen2 = mysql_fetch_assoc($almacen2);


$query_pedido ="SELECT sum(cantidad)FROM despachos where consumibles='$row_almacen[consumible]'";
$pedido = mysql_query($query_pedido, $conexion) or die(mysql_error());
$row_pedido = mysql_fetch_assoc($pedido);	

$disponible=$row_almacen2["sum(cantidad)"]-$row_pedido["sum(cantidad)"];

if($row_almacen["cantidad"]>$disponible){
	echo "<script type=\"text/javascript\">alert ('No se puede eliminar este registro debido a que la cantidad a eliminar supera a la cantidad Disponible');  location.href='consultar_almacen.php' </script>";
  							exit;
	
}

//eliminamos 

mysql_select_db($database_conexion, $conexion);
$sql="delete  FROM  almacen where id_almacen=$id";
$verificar=mysql_query($sql,$conexion) or die(mysql_error());

if($verificar){
	echo"<script type=\"text/javascript\">alert ('Datos Eliminado'); location.href='consultar_almacen.php' </script>";
}
else{
	echo"<script type=\"text/javascript\">alert ('Error'); location.href='consultar_almacen.php' </script>";
	
}//fin de l primer else



mysql_free_result($almacen);
?>