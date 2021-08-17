<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Cargado por meses</title>
<link rel="stylesheet" type="text/css" href="../estilos/hoja.css">
</head>
<body>
<?php
require("../includes/navbar.php");        
?>
	<table>
		<tr>
		  <td colspan="2"><h1>CARGADO POR MESES</h1></td>
		</tr>
		<tr>
		  <th>Fecha</th>
		  <th>Importe</th>              
		</tr> 

<?php  
foreach(cargadoMeses($_SESSION["usuario"]) as $r): 
?>
		<tr>
          <td><?php echo($r->fecha_valor); ?></td>
          <td><?php echo($r->suma); ?></td>
		</tr>

<?php  
endforeach; 
?>
   </table>
<?php
require("../includes/footer.php");
?>