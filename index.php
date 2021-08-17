<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Login</title>
<link rel="stylesheet" type="text/css" href="estilos/hoja.css">
</head>
<body>
<?php
function verCookie(){
    global $usuario;
	global $password;
	global $recordar;
    if(isset($_COOKIE["usuario"])){ // si existe cookie inicializo los campos del formulario con la cookie
	   $recibido = json_decode($_COOKIE["usuario"]);
	   $usuario = $recibido->usuario;
	   $password = $recibido->password;
	   $recordar = $recibido->recordar;
    }
    else{ // si no existe cookie inicializo los campos del formulario a NULL
	   $usuario = NULL;
	   $password = NULL;
	   $recordar = NULL;
    }
}
session_start();
if(isset($_POST["usuario"]) && isset($_POST["password"])){ // si he hecho POST correctamente
  $usuario = trim(htmlentities($_POST["usuario"], ENT_QUOTES, "UTF-8"));
  $password = trim(htmlentities($_POST["password"], ENT_QUOTES, "UTF-8"));
  require("./modelo/modelo.php");
	  if(!password_verify($password, contrasenaUsuario($usuario))){ // si el usuario no existe o el usuario existe pero la contraseña no es la suya
		verCookie(); // inicializo los campos del formulario
	  }
	  else{ // usuario registrado
        $_SESSION["usuario"] = $usuario;
		if(!$tokenUsuario = tokenUsuario($usuario)){ // si es la primera vez que inicia sesión
		  $_SESSION["token"] = bin2hex(openssl_random_pseudo_bytes (24)); // generamos un token y lo asisnamos al token se sesión
		  updateFechaUsuario($usuario, date("Y-m")); // actualizamos el campo fecha del usuario al año/mes actual
		  updateTokenUsuario($usuario, $_SESSION["token"]); // actualizamos el campo token del usuario con el token de sesión generado
          if(isset($_POST["recordar"])){
		    setcookie("usuario", json_encode($_POST), time() + 86400, "/"); // creo la cookie; durante 24 horas podrá loguearse con la cookie
		  }
		}
		else{ // si no es la primera vez que inicia sesión
		  $_SESSION["token"] = $tokenUsuario; // el token de sesión es el campo token del usuario
          if(isset($_POST["recordar"])){
		    setcookie("usuario", json_encode($_POST), time() + 86400, "/"); // durante 24 horas podrá loguearse con la cookie
		  }
          else if(isset($_COOKIE["usuario"])){ // si la cookie existe (antes deseaba recordar) pero ya no deseo recordar
            setcookie("usuario", "", time() - 3600, "/"); // elimino la cookie
          }
		}
		header("location: usuarios_registrados");
	  }
}
else{ // si no he hecho POST o no he rellenado todos los campos
    verCookie(); // inicializo los campos del formulario
}
?>
<form class="formulario" method="POST" action="">
  <h1>Introduce tus datos</h1>
    <div class="formulario">
        <label>Usuario:</label>
        <input class="formulario" type="text" name="usuario" pattern="[a-zA-Z0-9]+" value="<?php echo $usuario; ?>" autofocus required />
    </div>
    <div class="formulario">
        <label>Contraseña:</label>
        <input class="formulario" type="password" name="password" value="<?php echo $password; ?>" required />
    </div>
    <div class="formulario">
        <label>Recordar:</label>
        <input class="formulario" type="checkbox" name="recordar"  <?php echo $recordar == "on" ? 'checked' : ''; ?> />
    </div>
	<div class="enviar">
    	<input class="enviar" type="submit" name="enviar" value="Log In"></input>
	</div>
</form>
<?php
require("./includes/footer.php");
?>