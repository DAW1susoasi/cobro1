<?php
require("../modelo/modelo.php");
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
if(!isset($_SESSION["usuario"]) || !isset($_SESSION["token"]) || hash_equals(tokenUsuario($_SESSION["usuario"]), $_SESSION["token"]) === FALSE){ // si el usuario no ha iniciado sesion o el token de sesion no es igual al token del usuario en la base de datos
  header("location: ../");
}
?>
<div class="header">
	<div class="flex-container">
		<nav>
			<ul>
			  <li><a href="http://localhost/cobro1/cerrar_sesion/">Cerrar sesión</a></li>
			  <li><a href="http://localhost/cobro1/cambia_contra/">Cambiar contraseña</a></li>
			  <li><a href="#">Cobrar/descargar</a>
				<ul class="submenu">
					<li><a href="http://localhost/cobro1/formulario_semana?d=cobdes">Cobrar/descargar</a></li>
					<li><a href="http://localhost/cobro1/formulario_semana?d=cobdes_id">Por Id</a></li>
					<li><a href="http://localhost/cobro1/formulario_semana?d=cobdes_importe">Por importe</a></li>
				</ul>
			  </li>
			  <li><a href="http://localhost/cobro1/crud">Insertar recibos</a></li>
			  <li><a href="#">Informes</a>
				<ul class="submenu">
				  <li><a href="http://localhost/cobro1/cobrado_meses">Cobrado por meses y semanas</a></li>
				  <li><a href="http://localhost/cobro1/descargado_meses">Descargado por meses y semanas</a></li>
				  <li><a href="http://localhost/cobro1/cargado_meses">Cargado por meses</a></li>
				  <li><a href="http://localhost/cobro1/pendiente_meses">Pendiente por meses</a></li>
				  <li><a href="http://localhost/cobro1/formulario_mes?d=quedaron_pendientes_mes">Quedaron pendientes en mes</a></li>
				  <li><a href="http://localhost/cobro1/formulario_mes?d=recibos_mes">Recibos mes</a></li>
				</ul>
			  </li>
			  <li><a onclick="javascript:return confirm('Cerrar mes. ¿Continuar?');" href="http://localhost/cobro1/cerrar_mes1">Cerrar mes</a></li>
			</ul>
		</nav>
	</div>
</div>