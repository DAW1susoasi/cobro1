<?php

function conectar(){
/* connection to the database
*/
  try{       
	$pdo = new PDO('mysql:host=localhost;dbname=cobro', 'root', '');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET CHARACTER SET UTF8");
	return($pdo);
  }catch(Exception $e){    
	echo("Error al conectar con la base de datos: " . die($e->getMessage()));
  }
}

function nuevoCobrador($id_cobrador, $contrasena){
       $con = conectar();
       try{
		  $sql = "INSERT INTO cobradores (id_cobrador, contrasena) VALUES (?, ?)";
		  $stm = $con->prepare($sql);		
		  $stm->execute(array($id_cobrador, $contrasena));
		  echo "Registro insertado";
		  $stm->closeCursor();
	   }catch(Exception $e){			
		  echo("Error en la consulta: " . die($e->getMessage()));
	   }finally{
		$con = null;
	   }
}

function contrasenaUsuario($id_cobrador){
 /* index.php
    when the user tries to login, if the user exists it return its password or NULL otherwise
*/
  $con = conectar();
  try{
	$sql = "SELECT id_cobrador FROM cobradores WHERE id_cobrador= ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador));
	if($stm->rowcount() === 1){ 
	  $sql = "SELECT contrasena FROM cobradores WHERE id_cobrador= ?";
	  $stm = $con->prepare($sql);
	  $stm->execute(array($id_cobrador));
	  $r = $stm->fetch(PDO::FETCH_OBJ)->contrasena;
	}
	else{
	  $r = NULL;
	}
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function updateFechaUsuario($id_cobrador, $fecha){
 /* index.php
    when the user logs in successfully for the first time, 
    we update its date field to the current year-month
*/   
  $con = conectar();
  try{
	$sql = "UPDATE cobradores SET fecha = ? WHERE id_cobrador = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($fecha, $id_cobrador));
	$stm->closeCursor();
  }catch (Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function updateTokenUsuario($id_cobrador, $token){
 /* index.php
    when the user logs in successfully, 
    we update their token
*/   
  $con = conectar();
  try{
	$sql = "UPDATE cobradores SET token = ? WHERE id_cobrador = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($token, $id_cobrador));
	$stm->closeCursor();
  }catch (Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function fechaUsuario($id_cobrador){
/* index.php, cerrar_mes.php, crud.php
   return the year/month in which the collector is managing his receipts
*/ 
  $con = conectar();
  try{           
	$sql = "SELECT fecha FROM cobradores where id_cobrador = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador));
	$r = $stm->fetch(PDO::FETCH_OBJ)->fecha;
	$stm->closeCursor();
	return $r;
  }catch (Exception $e){            
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function tokenUsuario($id_cobrador){
/* index.php, usuarios_registrados.php
   return the collector token
*/
  $con = conectar();
  try{           
	$sql = "SELECT token FROM cobradores where id_cobrador = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador));
	$r = $stm->fetch(PDO::FETCH_OBJ)->token;
	$stm->closeCursor();
	return $r;
  }catch (Exception $e){            
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function existeRecibo($id_recibo){
/* crud.php
   return if there is a receipt by id
*/
  $con = conectar();
  try{
	$sql = "SELECT id_recibo FROM recibos where id_recibo = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_recibo));
	$r = $stm->rowcount();
	$stm->closeCursor();
	return $r;
  }catch (Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function listar($id_cobrador){
/* crud.php
   return the number of receipts inserted by the collector
   in the temporary table
*/   
  $con = conectar();
  try{
	$sql = "SELECT R.id_recibo FROM recibos R INNER JOIN recibos_temp RT 
						ON R.id_recibo = RT.id_recibo 
						WHERE R.id_cobrador = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador));
	$r = $stm->rowcount();
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function busqueda($id_cobrador,$empezar_desde, $tamano_pagina){
/* crud.php
   return the receipts inserted by the collector in the temporary table
*/
  $con = conectar();
  try{
	$sql = "SELECT R.id_recibo, R.importe FROM recibos R INNER JOIN recibos_temp RT
						ON R.id_recibo = RT.id_recibo 
						WHERE R.id_cobrador = ? ORDER BY R.auto DESC LIMIT $empezar_desde, $tamano_pagina";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador));
	$r = $stm->fetchall(PDO::FETCH_OBJ);
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}   

function eliminarTodos_recibos_temp($id_cobrador){
/* crud.php
   removes all receipts inserted by the collector
   in the temporary table
*/  
  $con = conectar();
  try{           
	$stm = $con->prepare("DELETE RT FROM recibos_temp RT INNER JOIN recibos R 
							ON RT.id_recibo = R.id_recibo
							WHERE R.id_cobrador = ?");
	$stm->execute(array($id_cobrador));
	$stm->closeCursor();
  }catch (Exception $e){
	  echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function actualizarImporte($importe, $id){
/* crud.php
   update the amount of a receipt by id
*/
  $con = conectar();
  try{
	$sql = "UPDATE recibos SET importe = ? WHERE id_recibo = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($importe, $id));
	$stm->closeCursor();
  }catch (Exception $e){
	  echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function insertar($id_cobrador, $fecha, $id_recibo, $importe){
/* crud.php
   insert a new receipt from the collector in the receipts table
   and in the temporary table
*/
  $con = conectar();
  try{
    $sql = "INSERT INTO recibos (id_recibo,id_cobrador,fecha_valor,fecha,importe) VALUES (? ,?, ?, ?, ?)";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_recibo, $id_cobrador, $fecha, $fecha, $importe));
	$stm = NULL;
	$sql = "INSERT INTO recibos_temp (id_recibo) VALUES (?)";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_recibo));
	$stm->closeCursor();
  }catch (Exception $e){
  echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function listarPendiente($id_cobrador){
/* cobdes_recibos.php, cerrar_mes2.php
   return the number of receipts pending collection/discharge
*/
  $con = conectar();
  try{
	$sql = "SELECT id_recibo FROM recibos WHERE id_cobrador = ? AND semana_cobro = 0 AND semana_descargo = 0";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador));
	$r = $stm->rowcount();
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function obtener($id_recibo){
/* recibos_mes.php, crud.php, cerrar_mes2.php
   return the receipt data by id
*/
  $con = conectar();
  try{
	$stm = $con->prepare("SELECT * FROM recibos WHERE id_recibo = ?");
	$stm->execute(array($id_recibo));
	$r = $stm->fetch(PDO::FETCH_OBJ);
	$stm->closeCursor();
	return $r;
  }catch (Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function eliminarRecibo($id){
/* recibos_mes.php, crud.php
   delete a receipt by id
*/
  $con = conectar();
  try{
	$stm = $con->prepare("DELETE FROM recibos WHERE id_recibo = ?");
	$stm->execute(array($id));
	$stm->closeCursor();
  }catch (Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}



function actualizarTodo($importe, $id, $observaciones, $cobrado, $descargado){
/* recibos_mes.php, cerrar_mes2.php
   update importe, observaciones, semana_cobro and semana_descargo
    of a receipt by id
*/
  $con = conectar();
  try{
	$sql = "UPDATE recibos SET importe = ?, observaciones = ?, semana_cobro = ?, semana_descargo = ? WHERE id_recibo = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($importe, $observaciones, $cobrado, $descargado, $id));
	$stm->closeCursor();
  }catch (Exception $e){
	  echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function recibosPendientesImporte($id_cobrador, $importe){
/* cobdes_recibos_importe.php
   return the receipts pending collection/discharge from the collector by amount
*/
  $con = conectar();
  try{
	$sql = "SELECT * FROM recibos WHERE id_cobrador = ? AND importe = ? AND semana_cobro = 0 AND semana_descargo = 0 ORDER BY fecha_valor";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador, $importe));
	$r = $stm->fetchall(PDO::FETCH_OBJ);
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	  echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function recibosPendientes_id($id_cobrador, $id_recibo){
/* cobdes_recibos_id.php
   return the receipt pending collection/discharge from the collector by id
*/
  $con = conectar();
  try{
	$sql = "SELECT * FROM recibos WHERE id_cobrador = ? AND id_recibo = ? AND semana_cobro = 0 AND semana_descargo = 0";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador, $id_recibo));
	$r = $stm->fetch(PDO::FETCH_OBJ);
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	  echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function recibosPendientes($id_cobrador, $empezar_desde, $tamano_pagina){
/* cobdes_recibos.php, cerrar_mes2.php
   return the receipts pending collection/discharge from the collector
*/
  $con = conectar();
  try{
	$sql = "SELECT * FROM recibos WHERE id_cobrador = ? AND semana_cobro = 0 AND semana_descargo = 0 ORDER BY importe, fecha_valor LIMIT $empezar_desde, $tamano_pagina";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador));
	$r = $stm->fetchall(PDO::FETCH_OBJ);
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	  echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function cobrarRecibo($id_recibo, $semana){
/* cobdes_recibos.php, cobdes_recibos_id.php, cobdes_recibos_importe.php
   update the receipt semana_cobro field
*/
  $con = conectar();
  try{
	$sql = "UPDATE recibos SET semana_cobro = ? WHERE id_recibo = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($semana, $id_recibo));
  }catch (Exception $e){
    echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$stm = NULL;
	$con = NULL;		
  }
}

function descargarRecibo($id_recibo, $semana){
/* cobdes_recibos_id.php, cobdes_recibos_importe.php
   update the receipt semana_descargo field
*/
  $con = conectar();
  try{
	$sql = "UPDATE recibos SET semana_descargo = ? WHERE id_recibo = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($semana, $id_recibo));
	$stm->closeCursor();
  }catch (Exception $e){
    echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function cobradoMeses($id_cobrador){
/* cobrado_meses.php
   return how much the collector has collected for months and weeks
*/
  $con = conectar();
  try{
	$sql = "SELECT fecha, semana_cobro, Sum(importe)/100 AS suma
				FROM recibos
				GROUP BY fecha, semana_cobro, id_cobrador
				HAVING (((semana_cobro)>0) AND (id_cobrador)=?)
				ORDER BY fecha DESC , semana_cobro DESC";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador));
	$r = $stm->fetchall(PDO::FETCH_OBJ);
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function descargadoMeses($id_cobrador){
/* descargado_meses.php
   return how much has been discharged to the collector for months and weeks
*/
  $con = conectar();
  try{
	$sql = "SELECT fecha, semana_descargo, Sum(importe)/100 AS suma
				FROM recibos
				GROUP BY fecha, semana_descargo, id_cobrador
				HAVING (((semana_descargo)>0) AND (id_cobrador)=?)
				ORDER BY fecha DESC , semana_descargo DESC";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador));
	$r = $stm->fetchall(PDO::FETCH_OBJ);
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function cargadoMeses($id_cobrador){
/* cargado_meses.php
   return how much the collector has been charged for months
*/
  $con = conectar();
  try{
	$sql = "SELECT fecha_valor, Sum(importe)/100 AS suma
				FROM recibos
				WHERE id_cobrador = ?
				GROUP BY fecha_valor";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador));
	$r = $stm->fetchall(PDO::FETCH_OBJ);
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function pendienteMeses($id_cobrador){
/* pendiente_meses.php
   return how much the collector has left
   pending collection/discharge for months
*/
  $con = conectar();
  try{
	$sql = "SELECT RT.fecha, Sum(R.importe)/100 AS suma
				FROM recibos R INNER JOIN quedo_pendiente RT ON R.id_recibo = RT.id_recibo WHERE R.id_cobrador = ?
				GROUP BY RT.fecha ";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador));
	$r = $stm->fetchall(PDO::FETCH_OBJ);
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function saldoPendiente($id_cobrador){
/* cobdes_recibos.php, cerrar_mes2.php
   return how much the collector has pending collection/discharge 
*/
  $con = conectar();
  try{
	$sql = "SELECT Sum(importe)/100 AS suma FROM recibos WHERE semana_cobro = 0 AND semana_descargo = 0 AND id_cobrador= ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador));
	$r = $stm->fetch(PDO::FETCH_OBJ)->suma;
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function totalIntroducido($id_cobrador){
/* crud.php
   return how much the collector has inserted into the temporary table
*/   
  $con = conectar();
  try{      
	$sql = "SELECT Sum(importe)/100 AS suma FROM recibos R INNER JOIN recibos_temp RT ON R.id_recibo = RT.id_recibo WHERE R.id_cobrador = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador));
	$r = $stm->fetch(PDO::FETCH_OBJ)->suma;
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){          
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function recibos_mes($id_cobrador, $fecha){
/* recibos_mes.php
   return the number of receipts inserted by the collector
   in the table receipts a certain year/month
*/
  $con = conectar();
  try{
	$sql = "SELECT id_recibo FROM recibos WHERE fecha_valor = ? AND id_cobrador = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($fecha, $id_cobrador));
	$r = $stm->rowcount();
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function total_recibos_mes($id_cobrador, $fecha){
/* recibos_mes.php
   return how much a collector has been charged
   a certain year / month
*/
  $con = conectar();
  try{
	$sql = "SELECT Sum(importe)/100 AS suma FROM recibos WHERE fecha_valor = ? AND id_cobrador = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($fecha, $id_cobrador));
	$r = $stm->fetch(PDO::FETCH_OBJ);
	$stm->closeCursor();
	return $r->suma;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function recibos_mes_busqueda($id_cobrador, $empezar_desde, $tamano_pagina, $fecha){
/* recibos_mes.php
   return the charged receipts to a collector
   a certain year / month
*/
  $con = conectar();
  try{
	$sql = "SELECT * FROM recibos WHERE fecha_valor = ? AND id_cobrador = ? ORDER BY recibos.importe LIMIT $empezar_desde, $tamano_pagina";
	$stm = $con->prepare($sql);
	$stm->execute(array($fecha, $id_cobrador));
	$r = $stm->fetchall(PDO::FETCH_OBJ);
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function cuantosPendientes($id_cobrador, $fecha){
/* quedaron_pendientes_mes.php
   return the number of receipts that were pending
   collection/discharge a certain year/month
*/
  $con = conectar();
  try{
	$sql = "SELECT RT.id_recibo FROM recibos R INNER JOIN quedo_pendiente RT 
						ON R.id_recibo = RT.id_recibo 
						WHERE R.id_cobrador = ? AND 
						RT.fecha = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador, $fecha));
	$r = $stm->rowcount();
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function cuantoPendiente($id_cobrador, $fecha){
/* quedaron_pendientes_mes.php
   return how much it left pending collection/discharge
   a collector a certain year/month
*/
  $con = conectar();
  try{
	$sql = "SELECT Sum(importe)/100 AS suma FROM recibos R INNER JOIN quedo_pendiente RT 
						ON R.id_recibo = RT.id_recibo 
						WHERE R.id_cobrador = ? AND 
						RT.fecha = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador, $fecha));
	$r = $stm->fetch(PDO::FETCH_OBJ);
	$stm->closeCursor();
	return $r->suma;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function cuantosPendientesBusqueda($id_cobrador, $empezar_desde, $tamano_pagina, $fecha){
/* quedaron_pendientes_mes.php
   return the receipts left pending collection/discharge
   a collector a certain year/month
*/
  $con = conectar();
  try{
	$sql = "SELECT RT.id_recibo, R.fecha, R.importe, R.semana_cobro, R.semana_descargo, R.observaciones FROM recibos R INNER JOIN quedo_pendiente RT 
                            ON R.id_recibo = RT.id_recibo 
                            WHERE R.id_cobrador = ? AND 
                            RT.fecha = ?
                            ORDER BY R.importe LIMIT $empezar_desde, $tamano_pagina";
	$stm = $con->prepare($sql);
	$stm->execute(array($id_cobrador, $fecha));
	$r = $stm->fetchall(PDO::FETCH_OBJ);
	$stm->closeCursor();
	return $r;
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}    

function cerrarMes($id, $fecha){
/* cerrar_mes.php
   I update the collector date to the following month
   I insert the uncollected/discharged receipts in the table quedo_pendiente
   I update the date of the uncollected/discharged receipts to the following month
*/
  $con = conectar();
  try{
	$sql = "UPDATE cobradores SET fecha = ? WHERE id_cobrador=?";
	$stm = $con->prepare($sql);
	$stm->execute(array($fecha, $id));
	$stm = NULL;
	$sql = "INSERT INTO quedo_pendiente (id_recibo, fecha)
			   SELECT id_recibo, fecha FROM recibos 
			   WHERE semana_cobro= 0 AND semana_descargo = 0 AND id_cobrador = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($id));
	$stm = NULL;
	$sql = "UPDATE recibos SET fecha = ? WHERE semana_cobro = 0 AND semana_descargo = 0 AND id_cobrador = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($fecha, $id));
	$stm->closeCursor();
  }catch (Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}

function cambiaContra($cifrado,$idi){
/* cambia_contra.php
   change a collector password
*/
  $con = conectar();
  try{
	$sql = "UPDATE cobradores SET contrasena = ? WHERE id_cobrador = ?";
	$stm = $con->prepare($sql);
	$stm->execute(array($cifrado,$idi));
	$stm->closeCursor();
  }catch(Exception $e){
	echo("Error en la consulta: " . die($e->getMessage()));
  }finally{
	$con = NULL;		
  }
}
?>