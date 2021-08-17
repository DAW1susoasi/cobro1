<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Nuevo cobrador</title>
<link rel="stylesheet" type="text/css" href="estilos/hoja.css">
</head>
<body>
<?php
	
if(isset($_POST["usuario"]) && isset($_POST["contrasena"])){
    
    require("./modelo/modelo.php");
	
	$usuario = trim(htmlentities($_POST["usuario"], ENT_QUOTES, "UTF-8"));
	
	$contrasena = trim(htmlentities($_POST["contrasena"], ENT_QUOTES, "UTF-8"));
	
	$cifrado = password_hash($contrasena, PASSWORD_DEFAULT);

    nuevoCobrador($usuario, $cifrado);
}
?>
<form class="formulario" method="POST" action="">
	<h1>Nuevo cobrador</h1>
    <div class="formulario">
        <label>Usuario:</label>
        <input type="text" name="usuario" class="formulario" autofocus>
	</div>
	<div class="formulario">
		<label>Contraseña:</label>
		<input type="text" name="contrasena" class="formulario">
	</div>
	<div class="enviar">
    	<input class="enviar" type="submit" name="enviar" value="Registrar"></input>
	</div>
</form>
<?php
require("./includes/footer.php");
?>