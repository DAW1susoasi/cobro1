<?php
require("../includes/navbar.php");

cerrarMes($_SESSION["usuario"], date("Y-m", strtotime(fechaUsuario($_SESSION["usuario"])."next month")));

header("location: ../cerrar_mes");
?>