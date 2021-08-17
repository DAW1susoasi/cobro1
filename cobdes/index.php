<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Cobrar y Descargar recibos</title>
<link rel="stylesheet" type="text/css" href="../estilos/hoja.css">
</head>
<body>
<?php
require("../includes/navbar.php");
if(!isset($_SESSION["semana"])){
	header('Location: ../formulario_semana/?d=cobdes');	
}
if(isset($_GET['action'])){
	switch($_GET['action']){
		case 'cobrar':
// recibo por GET el id del recibo y con él cobro el recibo
			if(base64_decode($_GET['id']) && existeRecibo(base64_decode($_GET['id']))){
            	cobrarRecibo(base64_decode($_GET["id"]), $_SESSION["semana"]);
			}
			break;
		case 'descargar':
// recibo por GET el id del recibo y con él descargo el recibo 
			if(base64_decode($_GET['id']) && existeRecibo(base64_decode($_GET['id']))){
            	descargarRecibo(base64_decode($_GET["id"]), $_SESSION["semana"]);
			}
			break;
    }
}
$num_filas = listarPendiente($_SESSION["usuario"]);
require("../includes/paginacion.php");
if(!$saldo_pendiente = saldoPendiente($_SESSION["usuario"])){
	$saldo_pendiente = 0;
}
?>
<div class="table-container"> 
 <table>
  <tr>
	<th WIDTH="148">Página</th>
	<th WIDTH="150">Importe</th>
	<th WIDTH="150">Id</th>
	<th WIDTH="100">Fecha</th>
	<th WIDTH="350">Observaciones</th>
  </tr>
  <tr>
    <td>

<?php require("../includes/botones_paginacion.php"); ?>

	</td>
    <td><?php echo $saldo_pendiente . " €"; ?></td>
    <td><?php echo $num_filas . " recibos"; ?></td>
	<td></td>
	<td></td>
  </tr>
<?php    
foreach(recibosPendientes($_SESSION["usuario"], $empezar_desde, $tamao_pagina) as $r): 
?>
  <tr>
<!-- Mandamos por GET a la misma página el action 'descargar' junto con el id 'id_recibo' que será descargado -->
   <td>
	   <a class="bot" href="?action=descargar&pagina=<?php echo $pagina; ?>&id=<?php echo base64_encode($r->id_recibo); ?>">
	   		<input type='button' value='Descargar'></a>
<!-- Mandamos por GET a la misma página el action 'cobrar' junto con el id 'id_recibo' que será cobrado -->
	   <a class="bot" href="?action=cobrar&pagina=<?php echo $pagina; ?>&id=<?php echo base64_encode($r->id_recibo); ?>">
		   <input type='button' value='Cobrar'></a>
   </td>
   <td><?php echo $r->importe; ?></td>
   <td><?php echo $r->id_recibo; ?></td>
   <td><?php echo $r->fecha_valor; ?></td>
   <td><?php echo $r->observaciones; ?></td>
  </tr>
<?php    
endforeach; 
?>
</table>    
</div>
<?php
require("../includes/footer.php");
?>