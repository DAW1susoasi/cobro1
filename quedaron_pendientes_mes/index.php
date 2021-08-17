<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Recibos no cobrados en mes</title>
<link rel="stylesheet" type="text/css" href="../estilos/hoja.css">
</head>
<body>
<?php
require("../includes/navbar.php");
if(!isset($_SESSION["fecha"])){
	header('Location: ../formulario_mes?d=quedaron_pendientes_mes');	
}
$num_filas = cuantosPendientes($_SESSION["usuario"], $_SESSION["fecha"]);
require("../includes/paginacion.php");
if(!$total_introducido = cuantoPendiente($_SESSION["usuario"], $_SESSION["fecha"])){
    $total_introducido = 0;
}

?>
<div class="table-container"> 
  <table>
	<tr>
	  <th WIDTH="150">Página</th>
      <th WIDTH="150">Importe</th>
      <th WIDTH="150">Id</th>
      <th WIDTH="100">Fecha</th>
      <th>Sem.Cob.</th>
      <th>Sem.Desc</th>
      <th WIDTH="350">Observaciones</th>
	</tr>
	<tr>
      <td>

<?php require("../includes/botones_paginacion.php"); ?>

	  </td>
	  <td><?php echo number_format($total_introducido, 2) . " €"; ?></td>
	  <td><?php echo $num_filas . " recibos"; ?></td>
	  <td></td>
	  <td></td>
	</tr>

<?php  
foreach(cuantosPendientesBusqueda($_SESSION["usuario"], $empezar_desde, $tamao_pagina, $_SESSION["fecha"]) as $r): 
?>
	<tr>
	  <td></td>
	  <td><?php echo($r->importe); ?></td>
	  <td><?php echo($r->id_recibo); ?></td>
	  <td><?php echo($r->fecha); ?></td>
      <td><?php echo $r->semana_cobro > 0 ? $r->semana_cobro : ''; ?></td>
      <td><?php echo $r->semana_descargo > 0 ? $r->semana_descargo : ''; ?></td>
	  <td><?php echo($r->observaciones); ?></td>
	</tr>
<?php   
endforeach; 
?>           
  </table>
</div>
<?php
require("../includes/footer.php");
?>