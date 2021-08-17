<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Recibos quedaron pendientes en mes</title>
<link rel="stylesheet" type="text/css" href="../estilos/hoja.css">
</head>
<body>
<?php
require("../includes/navbar.php"); 
if(isset($_GET["d"]) && isset($_POST["fecha"])){
	$valores = explode('-', $_POST["fecha"]);
	if(count($valores) == 2 && checkdate($valores[1], 01, $valores[0])){ // si es una fecha correcta
		$_SESSION["fecha"] = $_POST["fecha"];
		$location = "location: ../" . $_GET["d"];
        header($location);
    }
}
?>
<form class="formulario" method="POST" action="">
	<h1>Introduce tus datos</h1>
    <div class="formulario">
        <label>Fecha:</label>
        <input class="formulario" type="month" autofocus="autofocus" name="fecha" max="<?php echo date("Y-m") ?>" step="1" pattern="[0-9]{4}-[0-9]{2}" placeholder="año-mes" required />
    </div>
	<div class="enviar">
    	<input class="enviar" type="submit" name="enviar" value="Enviar"></input>
	</div>
</form>
<?php
require("../includes/footer.php");
?>