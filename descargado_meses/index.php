<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Descargado por meses y semanas</title>
<link rel="stylesheet" type="text/css" href="../estilos/hoja.css">
</head>
<body>
<?php
require("../includes/navbar.php");        
?>
	<table>
		<tr>
		  <td colspan="3"><h1>DESCARGADO POR MESES Y SEMANAS</h1></td>
		</tr>
		<tr>
		  <th>Fecha</th>
		  <th>Semana</th>
		  <th>Importe</th>              
		</tr> 

<?php  
foreach(descargadoMeses($_SESSION["usuario"]) as $r): ?>
		<tr>
		  <td><?php echo $r->fecha; ?></td>
		  <td><?php echo $r->semana_descargo; ?></td>
		  <td><?php echo $r->suma; ?></td>
		</tr>

<?php  
endforeach; ?>
   </table>
<?php
require("../includes/footer.php");
?>