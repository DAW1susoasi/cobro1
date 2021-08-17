<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Cobrar Descargar recibos</title>
<link rel="stylesheet" type="text/css" href="../estilos/hoja.css">
</head>
<body>
<?php
require("../includes/navbar.php");
if(isset($_GET["d"]) && isset($_POST["semana"]) && ctype_digit($_POST["semana"])){
    if($_POST["semana"] > 0 && $_POST["semana"] < 6){
        $_SESSION["semana"] = $_POST["semana"];
        $location = "location: ../" . $_GET["d"];
        header($location);
    }
}
?>
<form class="formulario" method="POST" action="">
	<h1>Introduce tus datos</h1>
    <div class="formulario">
        <label>Semana número:</label>
        <input class="formulario" type="text" name="semana" pattern="[1-5]" placeholder="1-5" autofocus required />
    </div>
	<div class="enviar">
    	<input class="enviar" type="submit" name="enviar" value="Enviar"></input>
	</div>
</form>
<?php
require("../includes/footer.php");
?>