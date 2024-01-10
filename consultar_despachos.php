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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_de = 10;
$pageNum_de = 0;
if (isset($_GET['pageNum_de'])) {
  $pageNum_de = $_GET['pageNum_de'];
}
$startRow_de = $pageNum_de * $maxRows_de;

mysql_select_db($database_conexion, $conexion);
$query_de = "SELECT * FROM despachos ";
$query_limit_de = sprintf("%s LIMIT %d, %d", $query_de, $startRow_de, $maxRows_de);
$de = mysql_query($query_limit_de, $conexion) or die(mysql_error());
$row_de = mysql_fetch_assoc($de);

if (isset($_GET['totalRows_de'])) {
  $totalRows_de = $_GET['totalRows_de'];
} else {
  $all_de = mysql_query($query_de);
  $totalRows_de = mysql_num_rows($all_de);
}
$totalPages_de = ceil($totalRows_de/$maxRows_de)-1;



$queryString_de = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_de") == false && 
        stristr($param, "totalRows_de") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_de = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_de = sprintf("&totalRows_de=%d%s", $totalRows_de, $queryString_de);

if($totalRows_de==0){
  echo "<script type=\"text/javascript\">alert ('Debe Cargar Registros en Despachos');  location.href='registrar_despachos.php' </script>";
   exit;
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="estilos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.Estilo14 {color: #5a7210;
	font-weight: bold;
	font-style: italic;
	font-size: 18px;
}
</style>

<script language="javascript">
<!--

function validar(){

			var valor=confirm('¿Esta seguro de Eliminar este Registro?');
			if(valor==false){
			return false;
			}
			else{
			return true;
			}
		
}
//-->
</script>
<style type="text/css">
.Estilo14 {color: #5a7210;
	font-weight: bold;
	font-style: italic;
	font-size: 18px;
}
</style>
</head>

<body>
<table width="658" align="center" cellpadding="2" class="bordes_tablas">
  <tr>
    <th colspan="6" bgcolor="#FFFFFF" scope="col"><span class="Estilo14">Consultar Despachos</span></th>
  </tr>
  <tr>
    <th width="92" bgcolor="#FFCC66" scope="col">Fecha</th>
    <th width="85" bgcolor="#FFCC66" scope="col">Cantidad</th>
    <th width="201" bgcolor="#FFCC66" scope="col">Oficina</th>
    <th width="147" bgcolor="#FFCC66" scope="col">Consumible</th>
    <th width="47" bgcolor="#FFCC66" scope="col">Opcion</th>
    <th width="47" bgcolor="#FFCC66" scope="col">Opcion</th>
  </tr>
  <?php do { 
  
  mysql_select_db($database_conexion, $conexion);
$query_co = "SELECT * FROM consumibles where id_copnsumible='$row_de[consumibles]'";
$co = mysql_query($query_co, $conexion) or die(mysql_error());
$row_co = mysql_fetch_assoc($co);
$totalRows_co = mysql_num_rows($co);



  $modulo=$cont%2;
			if($modulo!=0){
			$color="#9C9";
			$color2="class='Estilo12'";
			}else{
			$color="#FFFFFF";
			$color2="";
			} ?>
  <tr bgcolor="<?php echo $color; ?>">
    <td align="center"><div  <?php echo $color2;?> ><?php echo $row_de['fecha']; ?></div></td>
    <td align="center"><div  <?php echo $color2;?> ><?php echo $row_de['cantidad']; ?></div></td>
    <td align="center"><?php echo $row_de['oficina']; ?></td>
    <td align="center"><div  <?php echo $color2;?> ><?php echo $row_co['marca']." ".$row_co['modelo']." ".$row_co['color']; ?></div></td>
    <td align="center" bgcolor="<?php echo $color; ?>"><span class="Estilo1"><? echo "<a  href='modificar_despachos.php?id=$row_de[id_despachos]'>Modificar</a>";?></span></td>
    <td align="center" bgcolor="<?php echo $color; ?>"><span class="Estilo1"><? echo "<a  onClick='return validar()' href='eliminar_despachos.php?id=$row_de[id_despachos]'>Eliminar</a>"; ?></span></td>
  </tr>
  <?php 
  $cont++;
     } while ($row_de = mysql_fetch_assoc($de)); 
  ?>
</table>
<table border="0" align="center">
  <tr>
    <td><?php if ($pageNum_de > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_de=%d%s", $currentPage, 0, $queryString_de); ?>">Primero</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_de > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_de=%d%s", $currentPage, max(0, $pageNum_de - 1), $queryString_de); ?>">Anterior</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_de < $totalPages_de) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_de=%d%s", $currentPage, min($totalPages_de, $pageNum_de + 1), $queryString_de); ?>">Siguiente</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_de < $totalPages_de) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_de=%d%s", $currentPage, $totalPages_de, $queryString_de); ?>">&Uacute;ltimo</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($de);
?>
