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


  
  

$maxRows_consumibles = 20;
$pageNum_consumibles = 0;
if (isset($_GET['pageNum_consumibles'])) {
  $pageNum_consumibles = $_GET['pageNum_consumibles'];
}
$startRow_consumibles = $pageNum_consumibles * $maxRows_consumibles;

mysql_select_db($database_conexion, $conexion);
$query_consumibles = "SELECT * FROM consumibles";
$query_limit_consumibles = sprintf("%s LIMIT %d, %d", $query_consumibles, $startRow_consumibles, $maxRows_consumibles);
$consumibles = mysql_query($query_limit_consumibles, $conexion) or die(mysql_error());
$row_consumibles = mysql_fetch_assoc($consumibles);

if (isset($_GET['totalRows_consumibles'])) {
  $totalRows_consumibles = $_GET['totalRows_consumibles'];
} else {
  $all_consumibles = mysql_query($query_consumibles);
  $totalRows_consumibles = mysql_num_rows($all_consumibles);
}
$totalPages_consumibles = ceil($totalRows_consumibles/$maxRows_consumibles)-1;

$queryString_consumibles = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_consumibles") == false && 
        stristr($param, "totalRows_consumibles") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_consumibles = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_consumibles = sprintf("&totalRows_consumibles=%d%s", $totalRows_consumibles, $queryString_consumibles);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="estilos.css" rel="stylesheet" type="text/css" />
<title>Documento sin título</title>
<style type="text/css">
.Estilo14 {color: #5a7210;
	font-weight: bold;
	font-style: italic;
	font-size: 18px;
}
</style>
</head>

<body>
<table width="654" align="center" cellpadding="2" class="bordes_tablas">
  <tr>
    <th colspan="2" bgcolor="#FFFFFF" scope="col"><span class="Estilo14">Visualizar Despachos</span></th>
  </tr>
  <tr>
    <th width="535" bgcolor="#FFCC66" scope="col">Consumible</th>
    <th width="97" bgcolor="#FFCC66" scope="col">Despachos</th>
  </tr>
  <?php do { 

							
$query_pedido ="SELECT sum(cantidad)FROM despachos where consumibles='$row_consumibles[id_copnsumible]'";
$pedido = mysql_query($query_pedido, $conexion) or die(mysql_error());
$row_pedido = mysql_fetch_assoc($pedido);	

$disponible=$row_pedido["sum(cantidad)"];


  $modulo=$cont%2;
			if($modulo!=0){
			$color="#9C9";
			$color2="class='Estilo12'";
			}else{
			$color="#FFFFFF";
			$color2="";
			} ?>
  <tr bgcolor="<?php echo $color; ?>">
    <td align="center"><div  <?php echo $color2;?> ><?php echo $row_consumibles['tipo']; ?> <?php echo $row_consumibles['marca']; ?> <?php echo $row_consumibles['modelo']; ?> <?php echo $row_consumibles['color']; ?></div>
      <div  <?php echo $color2;?> ></div></td>
    <td align="center" bgcolor="<?php echo $color; ?>"><?=$disponible?></td>
  </tr>
  <?php 
	$cont++;
	} while ($row_consumibles = mysql_fetch_assoc($consumibles)); ?>
</table>
</body>
</html>