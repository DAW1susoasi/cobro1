<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Cambio de contraseña</title>
<link rel="stylesheet" type="text/css" href="../estilos/hoja.css">
</head>
<body>
<?php
require("../includes/navbar.php");
if(isset($_POST["contrasena"]) && isset($_POST["repContra"])){
  if(trim(htmlentities($_POST["contrasena"])) === trim(htmlentities($_POST["repContra"]))){  
      cambiaContra(password_hash(trim(htmlentities($_POST["contrasena"])), PASSWORD_DEFAULT), $_SESSION['usuario']);
      header("Location: ../usuarios_registrados");
  }
}
?>
<form class="formulario" method="POST" action="">
	<h1>Introduce tus datos</h1>
    <div class="formulario">
        <label>Nueva contaseña:</label>
        <input class="formulario" type="text" name="contrasena" autofocus required />
    </div>
    <div class="formulario">
        <label>Repetir contaseña:</label>
        <input class="formulario" type="text" name="repContra" required />
    </div>
    <div class="enviar">
    	<input class="enviar" type="submit" name="enviar" value="Enviar"></input>
	</div>
</form>
<?php
require("../includes/footer.php");
?>