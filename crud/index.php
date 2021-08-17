<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Insertar recibos</title>
<link rel="stylesheet" type="text/css" href="../estilos/hoja.css">
</head>
<body>
<?php
function setValores(){
	global $id; $id = NULL;
	global $importe; $importe = NULL;
	global $noModificable; $noModificable = "";
}
function existe_Recibo(){
    if(isset($_POST["importe"]) && isset($_POST["id"])){
        global $id; $id = trim(htmlentities($_POST["id"]));
        global $importe; $importe = trim(htmlentities($_POST["importe"]));
        if(ctype_digit($importe) && ctype_digit($id)){
            if($importe >= 10 && $importe <= 99999 && $id > 0 && $id <= 9999999999){
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
require("../includes/navbar.php");
	
if(isset($_GET['action'])){
	switch($_GET['action']){
		case 'actualizar':
// actualizo el recibo con los datos recibidos por POST			
            if(existe_Recibo()){
              actualizarImporte($importe, $id);
            }
			setValores();
			break;
		case 'registrar':
// inserto el recibo con los datos recibidos por POST
            if(!existe_Recibo()){
              insertar($_SESSION["usuario"], fechaUsuario($_SESSION["usuario"]), $id, $importe);
            }
			setValores();
			break;
		case 'editar':
// recibo por GET el id del recibo y con él obtengo el resto de campos que serán mostrados en el formulario para poder modificarlos y actualizarlos	
			if(base64_decode($_GET['id']) && existeRecibo(base64_decode($_GET['id']))){
                $datosRecibo = obtener(base64_decode($_GET['id']));
                $id = $datosRecibo->id_recibo;
                $importe = $datosRecibo->importe;
                $noModificable = "readonly='readonly'";
			}
			else{
				setValores();
			}
			break;
		case 'eliminar':
// recibo por GET el id del recibo y con él elimino el recibo			
			if(base64_decode($_GET['id']) && existeRecibo(base64_decode($_GET['id']))){
				eliminarRecibo(base64_decode($_GET['id']));
			}
			setValores();
			break;              
		case 'eliminar_todos':
// elimino todos los recibos del usuario de la tabla recibos_temp			
			eliminarTodos_recibos_temp($_SESSION["usuario"]);
			setValores();
			break;
	}
}
else{
	setValores();
}
$num_filas = listar($_SESSION["usuario"]);
	
require("../includes/paginacion.php");

if(!$total_introducido = totalIntroducido($_SESSION["usuario"])){
	
	$total_introducido = 0;
}
?>
<!-- al hacer POST, mandamos por GET a la misma página el action, que será 'actualizar' si pulsé el botón 'Editar' o 'registrar' en caso contrario  -->
<form  method="POST" action="?action=<?php echo $id ? 'actualizar' : 'registrar'; ?>&pagina=<?php echo $pagina; ?>">
<div class="table-container"> 
 <table>
 	<tr>
		<th WIDTH="130">Página</th>
		<th>Id</th>
    	<th>Importe</th>       
	 </tr> 
     <tr>
     	<td style="white-space: nowrap">

<?php require("../includes/botones_paginacion.php"); ?>

		</td>
		<td><input name='id' type='text' autofocus="autofocus" pattern="[0-9]{1,10}" style="width:125px"required
				<?php
				  if(!$id){ // si no pulsé el botón 'Editar'
					$encontrado = FALSE;
					while(!$encontrado){ // genero recibo automáticamente
					  $id = rand(1, 9999999999);
					  $encontrado = !existeRecibo($id) ? TRUE : FALSE;
					}
				  }
				  echo "value='$id' $noModificable";
				?>/>
		</td>
		<td><input name='importe' type='number' pattern="[0-9]{2,5}" min="10" style="width:125px"required
				value="<?php echo !$importe ? rand(10, 99999) : $importe; ?>" />
		
      </tr> 
      <tr>
		  <td>
              <input class="oculto" type='submit' name='enviar' value='Insertar/Actualizar'>
<!-- Mandamos por GET a la misma página el action 'eliminar_todos' -->
			  <a class="bot" href="?action=eliminar_todos"><input type='button' value='Eliminar todos'></a>
          </td>
		  <td><?php echo $num_filas . " recibos"; ?></td>
		  <td><?php echo $total_introducido . " €"; ?></td>
      </tr>
<?php  
foreach(busqueda($_SESSION["usuario"], $empezar_desde, $tamao_pagina) as $r): 
?>            
      <tr>
			<td>
<!-- Mandamos por GET a la misma página el action 'eliminar' junto con el id 'id_recibo' que será eliminado -->
				<a class="bot" onclick="javascript:return confirm('Eliminar recibo. ¿Continuar?');" href="?action=eliminar&pagina=<?php echo $pagina; ?>&id=<?php echo base64_encode($r->id_recibo); ?>">
					<input type='button' value='Eliminar'></a>
<!-- Mandamos por GET a la misma página el action 'editar' junto con el id 'id_recibo' que será editado -->
				<a class="bot" href="?action=editar&pagina=<?php echo $pagina; ?>&id=<?php echo base64_encode($r->id_recibo); ?>">
					<input type='button' class="Editar" value='Editar'></a>
            </td>
			<td><?php echo $r->id_recibo; ?></td>
			<td><?php echo $r->importe; ?></td>
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