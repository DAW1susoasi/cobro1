<?php
$tamao_pagina = 29;
	
$total_paginas = ceil($num_filas / $tamao_pagina);

if($total_paginas == 0) {
	
	$total_paginas = 1;  
}
if(!isset($_GET["pagina"]) || $_GET["pagina"] < 1 || $_GET["pagina"] > $total_paginas){
	
	$pagina = 1;
}
else {
	
	$pagina = $_GET["pagina"];
}
$anterior = $pagina - 1;
	
$siguiente = $pagina + 1;
	
$empezar_desde = ($pagina - 1) * $tamao_pagina;
?>