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
  $updateSQL = sprintf("UPDATE consumibles SET tipo=%s, marca=%s, modelo=%s, color=%s, impresora=%s WHERE id_copnsumible=%s",
                       GetSQLValueString($_POST['tipo'], "text"),
                       GetSQLValueString($_POST['marca'], "text"),
                       GetSQLValueString($_POST['modelo'], "text"),
                       GetSQLValueString($_POST['color'], "text"),
                       GetSQLValueString($_POST['impresora'], "text"),
                       GetSQLValueString($_POST['id_copnsumible'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
  
    if($Result1){
  echo "<script type=\"text/javascript\">alert ('Datos Actualizados');  location.href='consultar_consumibles.php' </script>";
  }else{
  echo "<script type=\"text/javascript\">alert ('Ocurrio un Error');  location.href='consultar_consumibles.php' </script>";
  exit;
  }
}

mysql_select_db($database_conexion, $conexion);
$query_consumibles = "SELECT * FROM consumibles where id_copnsumible='$_GET[id]'";
$consumibles = mysql_query($query_consumibles, $conexion) or die(mysql_error());
$row_consumibles = mysql_fetch_assoc($consumibles);
$totalRows_consumibles = mysql_num_rows($consumibles);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="estilos.css" rel="stylesheet" type="text/css" />
</head>
<script language="javascript">
<!--
function validar(){


		   if(document.form1.tipo.value==""){
		   alert("DEBE INGRESAR UN TIPO");
		   return false;
		   }
		    if(document.form1.marca.value==""){
		   alert("DEBE INGRESAR UNA MARCA");
		   return false;
		   }
		   if(document.form1.modelo.value==""){
		   alert("DEBE INGRESAR UN MODELO");
		   return false;
		   }
		   if(document.form1.color.value==""){
		   alert("DEBE INGRESAR EL COLOR DEL CONSUMIBLE");
		   return false;
		   }
		   if(document.form1.impresora.value==""){
		   alert("DEBE INGRESAR LA IMPRESORA");
		   return false;
		   }
		  
}
</script>
<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table width="419" align="center" cellpadding="4" class="bordes_tablas">
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap" bgcolor="#FFCC66"><strong>Actualizacion de Consumibles</strong></td>
    </tr>
    <tr valign="baseline">
      <td width="101" align="right" nowrap="nowrap"><strong>Tipo:</strong></td>
      <td width="248"><input name="tipo" type="text" value="<?php echo $row_consumibles['tipo']; ?>" size="40" maxlength="50" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><strong>Marca:</strong></td>
      <td><input name="marca" type="text" value="<?php echo $row_consumibles['marca']; ?>" size="40" maxlength="50" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><strong>Modelo:</strong></td>
      <td><input name="modelo" type="text" value="<?php echo $row_consumibles['modelo']; ?>" size="40" maxlength="50" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><strong>Color:</strong></td>
      <td><input name="color" type="text" value="<?php echo $row_consumibles['color']; ?>" size="40" maxlength="50" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><strong>Impresora:</strong></td>
      <td><input name="impresora" type="text" value="<?php echo $row_consumibles['impresora']; ?>" size="40" maxlength="50" /></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap" bgcolor="#FFCC66"><input type="submit" value="Actualizar Datos" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id_copnsumible" value="<?php echo $row_consumibles['id_copnsumible']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($consumibles);
?>
