<?php require_once('Connections/conexion.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	
	
///////////////////////////////////////////////////////////
mysql_select_db($database_conexion, $conexion);
$query_almacen = "SELECT * FROM almacen where id_almacen='$_POST[id_almacen]'";
$almacen = mysql_query($query_almacen, $conexion) or die(mysql_error());
$row_almacen = mysql_fetch_assoc($almacen);
$totalRows_almacen = mysql_num_rows($almacen);

if($row_almacen['consumible']==$_POST['consumible']){ 
$consumible=$row_almacen['consumible'];
}else{
	$consumible=$_POST['consumible'];
}

$query_almacen2 = "SELECT sum(cantidad) FROM almacen where consumible='$consumible'";
$almacen2 = mysql_query($query_almacen2, $conexion) or die(mysql_error());
$row_almacen2 = mysql_fetch_assoc($almacen2);


$query_pedido ="SELECT sum(cantidad)FROM despachos where consumibles='$consumible'";
$pedido = mysql_query($query_pedido, $conexion) or die(mysql_error());
$row_pedido = mysql_fetch_assoc($pedido);	



if($row_almacen['cantidad']>$_POST['cantidad']){
	
	$diferencia=$row_almacen['cantidad']-$_POST['cantidad'];
	$disponible=$row_almacen2["sum(cantidad)"]-$row_pedido["sum(cantidad)"]-$diferencia;

	if($disponible<0){
		echo "<script type=\"text/javascript\">alert ('No se puede modificar este registro debido a que la cantidad a modificar afecta las transacciones');  location.href='consultar_almacen.php' </script>";
  							exit;
	
		}
}
//
  
  $updateSQL = sprintf("UPDATE almacen SET fecha=%s, consumible=%s, cantidad=%s, observaciones=%s WHERE id_almacen=%s",
                       GetSQLValueString($_POST['fecha'], "date"),
                       GetSQLValueString($_POST['consumible'], "text"),
                       GetSQLValueString($_POST['cantidad'], "int"),
                       GetSQLValueString($_POST['observaciones'], "text"),
                       GetSQLValueString($_POST['id_almacen'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
    if($Result1){
  echo "<script type=\"text/javascript\">alert ('Datos Actualizados');  location.href='consultar_almacen.php' </script>";
  }else{
  echo "<script type=\"text/javascript\">alert ('Ocurrio un Error');  location.href='consultar_almacen.php' </script>";
  exit;
  }

}

mysql_select_db($database_conexion, $conexion);
$query_almacen = "SELECT * FROM almacen where id_almacen='$_GET[id]'";
$almacen = mysql_query($query_almacen, $conexion) or die(mysql_error());
$row_almacen = mysql_fetch_assoc($almacen);
$totalRows_almacen = mysql_num_rows($almacen);

mysql_select_db($database_conexion, $conexion);
$query_consumibles = "SELECT * FROM consumibles";
$consumibles = mysql_query($query_consumibles, $conexion) or die(mysql_error());
$row_consumibles = mysql_fetch_assoc($consumibles);
$totalRows_consumibles = mysql_num_rows($consumibles);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="estilos" rel="stylesheet" type="text/css" />
<link href="estilos.css" rel="stylesheet" type="text/css" />
<style type="text/css"> 
    @import url("jscalendar-1.0/calendar-win2k-cold-1.css");
    </style>
<script type="text/javascript" src="jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" src="jscalendar-1.0/lang/calendar-es.js"></script>
</head>
<script language="javascript">
<!--
function validar(){

if(document.form1.cantidad.value!=""){
			 var filtro = /^(\d)+$/i;
		      if (!filtro.test(document.getElementById('cantidad').value)){
				alert('SOLO PUEDE INGRESAR NUMEROS EN LA CANTIDAD');
				return false;
		   		}
				}

		   
		    if(document.form1.cantidad.value==""){
		   alert("DEBE INGRESAR UNA CANTIDAD");
		   return false;
		   }
		 
		  
}
</script>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" onsubmit="return validar()" name="form1" id="form1">
  <table align="center" cellpadding="4" class="bordes_tablas">
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap" bgcolor="#FFCC66"><strong>Modificar Almacen</strong></td>
    </tr>
    <tr valign="baseline">
      <td width="114" align="right" nowrap="nowrap"><strong>Fecha:</strong></td>
      <td width="428"><input name="fecha" type="text" id="fecha" value="<?php echo $row_almacen['fecha']; ?>" size="20" maxlength="10" readonly="readonly" />
        <button type="submit" id="cal-button-1" title="Clic Para Escoger la fecha">Fecha</button>
        <script type="text/javascript">
							Calendar.setup({
							  inputField    : "fecha",
							  ifFormat   : "%Y-%m-%d",
							  button        : "cal-button-1",
							  align         : "Tr"
							});
						  </script></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><strong>Consumible:</strong></td>
      <td><label for="consumible"></label>
        <select name="consumible" id="consumible">
          <?php
do {  
?>
          <option value="<?php echo $row_consumibles['id_copnsumible']?>"<?php if (!(strcmp($row_consumibles['id_copnsumible'], $row_almacen['consumible']))) {echo "selected=\"selected\"";} ?>><?php echo $row_consumibles['tipo']." ".$row_consumibles['marca']." ".$row_consumibles['modelo']." ".$row_consumibles['color']?></option>
          <?php
} while ($row_consumibles = mysql_fetch_assoc($consumibles));
  $rows = mysql_num_rows($consumibles);
  if($rows > 0) {
      mysql_data_seek($consumibles, 0);
	  $row_consumibles = mysql_fetch_assoc($consumibles);
  }
?>
        </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><strong>Cantidad:</strong></td>
      <td><input name="cantidad" type="text" value="<?php echo $row_almacen['cantidad']; ?>" size="20" maxlength="11" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="middle"><strong>Observaciones:</strong></td>
      <td><textarea name="observaciones" cols="40" rows="5"><?php echo $row_almacen['observaciones']; ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap" bgcolor="#FFCC66"><input type="submit" value="Actualizar Datos" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id_almacen" value="<?php echo $row_almacen['id_almacen']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($almacen);
?>
