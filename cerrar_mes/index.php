<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Cerrar mes</title>
<link rel="stylesheet" type="text/css" href="../estilos/hoja.css">
</head>
<body>
<?php
function setValores(){
	global $id; $id = NULL;
	global $importe; $importe = NULL;
	global $fecha; $fecha = NULL;
	global $cobrado; $cobrado = NULL;
	global $descargado; $descargado = NULL;
	global $observaciones; $observaciones = NULL;
}
function existe_Recibo(){
    if(isset($_POST["importe"]) && isset($_POST["id"]) && isset($_POST["cobrado"]) && isset($_POST["descargado"])){
        global $id; $id = trim(htmlentities($_POST["id"]));
        global $importe; $importe = trim(htmlentities($_POST["importe"]));
        global $cobrado; $cobrado = trim(htmlentities($_POST["cobrado"]));
        global $descargado; $descargado = trim(htmlentities($_POST["descargado"]));
        global $observaciones;
        if(isset($_POST["observaciones"])){
            $observaciones = trim(htmlentities($_POST["observaciones"]));
        }
        else{
            $observaciones = NULL;
        }
        if(ctype_digit($importe) && ctype_digit($id) && ctype_digit($cobrado) && ctype_digit($descargado)){
            if($importe >= 10 && $importe <= 99999 && $id > 0 && $id <= 9999999999){
                if(
					   ($id > 0 && $id <= 9999999999)
					&& ($importe > 0 && $importe <= 99999) 
				   	&& ($cobrado == 0 || $descargado == 0) 
				   	&& ($cobrado >= 0 && $cobrado < 6)
				   	&& ($descargado >= 0 && $descargado < 6)){
                    if(existeRecibo($id)){
                      return(true);
                    }
                    else{
                      return(false);
                    }
                }
            }
        }
    }
}
require("../includes/navbar.php");
if(isset($_GET['action'])){
	$pagina = $_GET["pagina"];
	switch($_GET['action']){
		case 'actualizar':
// actualizo el recibo con los datos recibidos por POST	
			if(existe_Recibo()){
				actualizarTodo($importe, $id, $observaciones, $cobrado, $descargado);
			}
            setValores();
			break;
		case 'editar':
// recibo por GET el id del recibo y con él obtengo el resto de campos que serán mostrados en el formulario para poder modificarlos y actualizarlos	             
			if(base64_decode($_GET['id']) && existeRecibo(base64_decode($_GET['id']))){
				$datosRecibo = obtener(base64_decode($_GET['id']));
                $id = $datosRecibo->id_recibo;
                $importe = $datosRecibo->importe;
                $fecha = $datosRecibo->fecha_valor;
				$cobrado = $datosRecibo->semana_cobro;
				$descargado = $datosRecibo->semana_descargo;
				$observaciones = $datosRecibo->observaciones;
			}
			else{
				setValores();
			}
			break;
	}
}
else{
	setValores();
}
$num_filas = listarPendiente($_SESSION["usuario"]);
require("../includes/paginacion.php");
if(!$total_introducido = saldoPendiente($_SESSION["usuario"])){
	$total_introducido = 0;
}	
?>
<!-- al hacer POST, mandamos por GET a la misma página el action 'actualizar'  -->
<form method="POST" action="?action=actualizar&pagina=<?php echo $pagina; ?>" >
<div class="table-container"> 
  <table>
    <tr>
		<input type="hidden" name="cobrado" value="<?php echo $cobrado; ?>" />
		<input type="hidden" name="descargado" value="<?php echo $descargado; ?>" />
	    <th WIDTH="150">Página</th>
        <th>Importe</th>
        <th>Id</th>
        <th WIDTH="70">Fecha</th>
        <th>Observaciones</th>
	</tr>
	<tr>
      <td style="white-space: nowrap">

<?php require("../includes/botones_paginacion.php"); ?>

	  </td>
	  <td><input type="number" name="importe" value="<?php echo $importe; ?>" pattern="[0-9]{2,5}" min="10" style="width:100px" readonly/></td>
	  <td><input type="text" name="id" value="<?php echo $id; ?>" pattern="[0-9]{1,10}" style="width:125px" readonly/></td>
	  <td><?php echo $fecha; ?></td>
	  <td><input name='observaciones' type='text' autofocus="autofocus" style="width:200px" value="<?php echo $observaciones; ?>" /></td>
	  <td><input class="oculto" type='submit' name='enviar' value='Actualizar'/></td>
	</tr> 
	<tr>
	  <td></td>
	  <td><?php echo number_format($total_introducido, 2) . " €"; ?></td>
	  <td><?php echo $num_filas . " recibos"; ?></td>
	  <td></td>
	  <td></td>
	</tr>
<?php 
foreach(recibosPendientes($_SESSION["usuario"], $empezar_desde, $tamao_pagina) as $r): 
?>
	<tr>
<!-- Mandamos por GET a la misma página el action 'editar' junto con el id 'id_recibo' que será editado -->
		<td>
			<a class="bot" href="?action=editar&pagina=<?php echo $pagina; ?>&id=<?php echo base64_encode($r->id_recibo); ?>">
				<input type='button' value='Editar'></a>
		</td>
		<td><?php echo $r->importe; ?></td>
		<td><?php echo $r->id_recibo; ?></td>
		<td><?php echo $r->fecha_valor; ?></td>
		<td><?php echo $r->observaciones; ?></td>
	</tr>
<?php 
endforeach; 
?>
  </table>
</div>
</form>
<?php
require("../includes/footer.php");
?>