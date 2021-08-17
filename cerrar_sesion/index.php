<?php
require("../includes/navbar.php");
$_SESSION = array(); // elimino todas las variables de sesión
if (ini_get("session.use_cookies")) { // elimino la cookie de sesión
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}
session_destroy(); // destruyo la sesión
header("location: ../");
?>