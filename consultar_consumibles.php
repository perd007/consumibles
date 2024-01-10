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

$maxRows_con = 10;
$pageNum_con = 0;
if (isset($_GET['pageNum_con'])) {
  $pageNum_con = $_GET['pageNum_con'];
}
$startRow_con = $pageNum_con * $maxRows_con;

mysql_select_db($database_conexion, $conexion);
$query_con = "SELECT * FROM consumibles";
$query_limit_con = sprintf("%s LIMIT %d, %d", $query_con, $startRow_con, $maxRows_con);
$con = mysql_query($query_limit_con, $conexion) or die(mysql_error());
$row_con = mysql_fetch_assoc($con);

if (isset($_GET['totalRows_con'])) {
  $totalRows_con = $_GET['totalRows_con'];
} else {
  $all_con = mysql_query($query_con);
  $totalRows_con = mysql_num_rows($all_con);
}
$totalPages_con = ceil($totalRows_con/$maxRows_con)-1;

$queryString_con = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_con") == false && 
        stristr($param, "totalRows_con") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_con = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_con = sprintf("&totalRows_con=%d%s", $totalRows_con, $queryString_con);

if($totalRows_con==0){
  echo "<script type=\"text/javascript\">alert ('Debe Registrar enConsumibles');  location.href='registrar_consumibles.php' </script>";
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

			var valor=confirm('¿Esta seguro de Eliminar este Consumible?');
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
    <th colspan="7" bgcolor="#FFFFFF" scope="col"><span class="Estilo14">Consultar Consumibles</span></th>
  </tr>
  <tr>
    <th width="84" bgcolor="#FFCC66" scope="col">Tipo </th>
    <th width="103" bgcolor="#FFCC66" scope="col">Marca </th>
    <th width="115" bgcolor="#FFCC66" scope="col">Modelo</th>
    <th width="98" bgcolor="#FFCC66" scope="col">Color</th>
    <th width="98" bgcolor="#FFCC66" scope="col">Impresora</th>
    <th width="47" bgcolor="#FFCC66" scope="col">Opcion</th>
    <th width="49" bgcolor="#FFCC66" scope="col">Opcion</th>
  </tr>
  <?php  
  do {
  $modulo=$cont%2;
			if($modulo!=0){
			$color="#9C9";
			$color2="class='Estilo12'";
			}else{
			$color="#FFFFFF";
			$color2="";
			} ?>
  
    <tr bgcolor="<?php echo $color; ?>">
      <td><div  <?php echo $color2;?> ><?php echo $row_con['tipo']; ?></div></td>
      <td><div  <?php echo $color2;?> ><?php echo $row_con['marca']; ?></div></td>
      <td><div  <?php echo $color2;?> ><?php echo $row_con['modelo']; ?></div></td>
      <td bgcolor="<?php echo $color; ?>"><?php echo $row_con['color']; ?></td>
      <td bgcolor="<?php echo $color; ?>"><?php echo $row_con['impresora']; ?></td>
      <td align="center" bgcolor="<?php echo $color; ?>"><span class="Estilo1"><? echo "<a  href='modificar_consumibles.php?id=$row_con[id_copnsumible]'>Modificar</a>";?></span></td>
      <td align="center" bgcolor="<?php echo $color; ?>"><span class="Estilo1"><? echo "<a  onClick='return validar()' href='eliminar_consumibles.php?id=$row_con[id_copnsumible]'>Eliminar</a>"; ?></span></td>
    </tr>
    <?php 
	$cont++;
	} while ($row_con = mysql_fetch_assoc($con)); ?>
</table>
<table border="0" align="center">
  <tr>
    <td><?php if ($pageNum_con > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_con=%d%s", $currentPage, 0, $queryString_con); ?>">Primero</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_con > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_con=%d%s", $currentPage, max(0, $pageNum_con - 1), $queryString_con); ?>">Anterior</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_con < $totalPages_con) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_con=%d%s", $currentPage, min($totalPages_con, $pageNum_con + 1), $queryString_con); ?>">Siguiente</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_con < $totalPages_con) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_con=%d%s", $currentPage, $totalPages_con, $queryString_con); ?>">&Uacute;ltimo</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($con);
?>
