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
  $updateSQL = sprintf("UPDATE despachos SET fecha=%s, consumibles=%s, cantidad=%s, oficina=%s WHERE id_despachos=%s",
                       GetSQLValueString($_POST['fecha'], "date"),
                       GetSQLValueString($_POST['consumible'], "text"),
                       GetSQLValueString($_POST['cantidad'], "int"),
                       GetSQLValueString($_POST['oficina'], "text"),
                       GetSQLValueString($_POST['id_despachos'], "int"));

  mysql_select_db($database_conexion, $conexion);
  $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
     if($Result1){
  echo "<script type=\"text/javascript\">alert ('Datos Actualizados');  location.href='consultar_despachos.php' </script>";
  }else{
  echo "<script type=\"text/javascript\">alert ('Ocurrio un Error');  location.href='consultar_despachos.php' </script>";
  exit;
  }
}

mysql_select_db($database_conexion, $conexion);
$query_desp = "SELECT * FROM despachos where id_despachos='$_GET[id]'";
$desp = mysql_query($query_desp, $conexion) or die(mysql_error());
$row_desp = mysql_fetch_assoc($desp);
$totalRows_desp = mysql_num_rows($desp);

mysql_select_db($database_conexion, $conexion);
$query_consumibles = "SELECT * FROM consumibles";
$consumibles = mysql_query($query_consumibles, $conexion) or die(mysql_error());
$row_consumibles = mysql_fetch_assoc($consumibles);
$totalRows_consumibles = mysql_num_rows($consumibles);

if($totalRows_consumibles==0){
  echo "<script type=\"text/javascript\">alert ('Debe Ingresar al Mens un Consumible');  location.href='registrar_consumibles.php' </script>";
   exit;
  }
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
		   if(document.form1.oficina.value==""){
		   alert("DEBE INGRESAR UNA OFICINA");
		   return false;
		   }
		 
		  
}
</script>
<body>
<form action="<?php echo $editFormAction; ?>" method="post" onsubmit="return validar()" name="form1" id="form1">
  <table align="center" cellpadding="4" class="bordes_tablas">
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap"><strong>Modificar Despachos de Consumibles</strong></td>
    </tr>
    <tr valign="baseline">
      <td width="104" align="right" nowrap="nowrap"><strong>Fecha:</strong></td>
      <td width="458"><input name="fecha" type="text" id="fecha" value="<?php echo $row_desp['fecha']; ?>" size="20" maxlength="10" readonly="readonly" />
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
      <td nowrap="nowrap" align="right"><strong>Consumibles:</strong></td>
      <td><select name="consumible" id="consumible">
        <?php
do {  
?>
        <option value="<?php echo $row_consumibles['id_copnsumible']?>"<?php if (!(strcmp($row_consumibles['id_copnsumible'], $row_desp['consumibles']))) {echo "selected=\"selected\"";} ?>><?php echo $row_consumibles['tipo']." ".$row_consumibles['marca']." ".$row_consumibles['modelo']." ".$row_consumibles['color']?></option>
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
      <td><input name="cantidad" id="cantidad" type="text" value="<?php echo $row_desp['cantidad']; ?>" size="20" maxlength="11" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><strong>Oficina:</strong></td>
      <td><input name="oficina" type="text" value="<?php echo $row_desp['oficina']; ?>" size="70" maxlength="100" /></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap"><input type="submit" value="ACTUALIZAR DATOS" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id_despachos" value="<?php echo $row_desp['id_despachos']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($desp);
?>
