<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Cobrar y Descargar recibos por id</title>
<link rel="stylesheet" type="text/css" href="../estilos/hoja.css">
</head>
<body>
<?php
require("../includes/navbar.php");
if(!isset($_SESSION["semana"])){
	header('Location: ../formulario_semana/?d=cobdes_id');	
}
if(isset($_GET['action'])){
	switch($_GET['action']){
		case 'cobrar':
            if(base64_decode($_GET['id']) && existeRecibo(base64_decode($_GET['id']))){
			     cobrarRecibo(base64_decode($_GET["id"]), $_SESSION["semana"]); 
            }
			break;
		case 'descargar':
            if(base64_decode($_GET['id']) && existeRecibo(base64_decode($_GET['id']))){
			     descargarRecibo($_GET["id"], $_SESSION["semana"]); 
            }
			break;
	}
}
?>
<form method="POST" action="">
<div class="table-container"> 
	<table>
	   <tr>
		<td WIDTH="147"></td>
		<th WIDTH="150">Id</th>
		<th WIDTH="150">Importe</th>
		<th WIDTH="100">Fecha</th>
		<th WIDTH="350">Observaciones</th>
	   </tr>
	   <tr>
		 <td></td>
		 <td><input name="id" type="text" autofocus="autofocus" pattern="[0-9]{1,10}" required></td>
		 <td></td>
		 <td></td>
		 <td></td>
	   </tr>
<?php
if(isset($_POST["id"]) && $r = recibosPendientes_id($_SESSION["usuario"], $_POST["id"])){
?>
	   <tr>
		 <td><a class="bot" href="?action=descargar&id=<?php echo base64_encode($r->id_recibo); ?>"><input type='button' value='Descargar'></a>
		     <a class="bot" href="?action=cobrar&id=<?php echo base64_encode($r->id_recibo); ?>"><input type='button' value='Cobrar'></a>
         </td>
		 <td><?php echo $r->id_recibo; ?></td>
		 <td><?php echo $r->importe; ?></td>
		 <td><?php echo $r->fecha_valor; ?></td>
		 <td><?php echo $r->observaciones; ?></td>
	   </tr>
<?php 
}
?>
	</table>   
</div>
</form>
<?php
require("../includes/footer.php");
?>