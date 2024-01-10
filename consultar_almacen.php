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

$maxRows_al = 10;
$pageNum_al = 0;
if (isset($_GET['pageNum_al'])) {
  $pageNum_al = $_GET['pageNum_al'];
}
$startRow_al = $pageNum_al * $maxRows_al;

mysql_select_db($database_conexion, $conexion);
$query_al = "SELECT * FROM almacen";
$query_limit_al = sprintf("%s LIMIT %d, %d", $query_al, $startRow_al, $maxRows_al);
$al = mysql_query($query_limit_al, $conexion) or die(mysql_error());
$row_al = mysql_fetch_assoc($al);

if (isset($_GET['totalRows_al'])) {
  $totalRows_al = $_GET['totalRows_al'];
} else {
  $all_al = mysql_query($query_al);
  $totalRows_al = mysql_num_rows($all_al);
}
$totalPages_al = ceil($totalRows_al/$maxRows_al)-1;

$queryString_al = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_al") == false && 
        stristr($param, "totalRows_al") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_al = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_al = sprintf("&totalRows_al=%d%s", $totalRows_al, $queryString_al);


if($totalRows_al==0){
  echo "<script type=\"text/javascript\">alert ('Debe Cargar Registros en Almacen');  location.href='cargar_almacen.php' </script>";
   exit;
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<body>
<table width="654" align="center" cellpadding="2" class="bordes_tablas">
  <tr>
    <th colspan="6" bgcolor="#FFFFFF" scope="col"><span class="Estilo14">Consultar Almacen</span></th>
  </tr>
  <tr>
    <th width="84" bgcolor="#FFCC66" scope="col">Fecha</th>
    <th width="124" bgcolor="#FFCC66" scope="col">Cantidad</th>
    <th width="147" bgcolor="#FFCC66" scope="col">Consumible</th>
    <th width="157" bgcolor="#FFCC66" scope="col">Observacion</th>
    <th width="47" bgcolor="#FFCC66" scope="col">Opcion</th>
    <th width="49" bgcolor="#FFCC66" scope="col">Opcion</th>
  </tr>
  <?php do { 
  
  mysql_select_db($database_conexion, $conexion);
$query_co = "SELECT * FROM consumibles where id_copnsumible='$row_al[consumible]'";
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
    <td align="center"><div  <?php echo $color2;?> ><?php echo $row_al['fecha']; ?></div></td>
    <td align="center"><div  <?php echo $color2;?> ><?php echo $row_al['cantidad']; ?></div></td>
    <td align="center"><div  <?php echo $color2;?> ><?php echo $row_co['marca']." ".$row_co['modelo']." ".$row_co['color']; ?></div></td>
    <td align="center" bgcolor="<?php echo $color; ?>"><?php echo $row_al['observaciones']; ?></td>
    <td align="center" bgcolor="<?php echo $color; ?>"><span class="Estilo1"><? echo "<a  href='modificar_almacen.php?id=$row_al[id_almacen]'>Modificar</a>";?></span></td>
    <td align="center" bgcolor="<?php echo $color; ?>"><span class="Estilo1"><? echo "<a  onClick='return validar()' href='eliminar_almacen.php?id=$row_al[id_almacen]'>Eliminar</a>"; ?></span></td>
  </tr>
  <?php 
  $cont++;
  } while ($row_al = mysql_fetch_assoc($al)); ?>
</table>
<table border="0" align="center">
  <tr>
    <td><?php if ($pageNum_al > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_al=%d%s", $currentPage, 0, $queryString_al); ?>">Primero</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_al > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_al=%d%s", $currentPage, max(0, $pageNum_al - 1), $queryString_al); ?>">Anterior</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_al < $totalPages_al) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_al=%d%s", $currentPage, min($totalPages_al, $pageNum_al + 1), $queryString_al); ?>">Siguiente</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_al < $totalPages_al) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_al=%d%s", $currentPage, $totalPages_al, $queryString_al); ?>">&Uacute;ltimo</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($al);

mysql_free_result($co);
?>
